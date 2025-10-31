<?php
namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\User;
use App\Models\UserCoupon;
use App\Models\Variation;
use App\Traits\Responses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    use Responses;

    public function index(Request $request)
    {
        $user = auth()->user();
        
        if(!$user){
             return $this->error_response('Unauthenticated', [], 401);
        }

        $cart = Cart::with([
            'product', 
            'product.images', 
            'variation', 
            'variation.color', 
            'variation.size'
        ])->where('user_id',  $user->id)
          ->where('status', 1)
          ->get();

        $cartData = [];
        $subtotal = 0;

        foreach ($cart as $item) {
            $cartData[] = [
                'id' => $item->id,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'total_price_product' => $item->total_price_product,
                'product' => [
                    'id' => $item->product->id,
                    'name_en' => $item->product->name_en,
                    'name_ar' => $item->product->name_ar,
                    'images' => $item->product->images,
                ],
                'variation' => $item->variation ? [
                    'id' => $item->variation->id,
                    'price_adjustment' => $item->variation->price_adjustment,
                    'color' => [
                        'id' => $item->variation->color->id,
                        'name' => $item->variation->color->name,
                    ],
                    'size' => [
                        'id' => $item->variation->size->id,
                        'name' => $item->variation->size->name,
                    ]
                ] : null,
                'created_at' => $item->created_at->toISOString(),
            ];

            $subtotal += $item->total_price_product;
        }

        // Get applied coupon and recalculate discount
        $couponDiscount = 0;
        $appliedCoupon = null;

        if ($cart->first() && $cart->first()->coupon_id) {
            $coupon = Coupon::find($cart->first()->coupon_id);
            if ($coupon) {
                // Calculate percentage discount
                $couponDiscount = ($subtotal * $coupon->amount) / 100;

                // Update discount in all cart items
                Cart::where('user_id', $user->id)
                    ->where('status', 1)
                    ->update(['discount_coupon' => $couponDiscount]);

                $appliedCoupon = [
                    'code' => $coupon->code,
                    'percentage' => $coupon->amount,
                    'discount' => $couponDiscount
                ];
            }
        }

        $summary = [
            'subtotal' => $subtotal,
            'coupon_discount' => $couponDiscount,
            'total' => $subtotal - $couponDiscount,
            'items_count' => $cart->sum('quantity'),
            'applied_coupon' => $appliedCoupon
        ];

        return $this->success_response('Cart retrieved successfully', [
            'items' => $cartData,
            'summary' => $summary
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variation_id' => 'nullable|exists:variations,id',
            'quantity' => 'required|integer',
        ]);

        $user = auth()->user();
        
        if(!$user){
             return $this->error_response('Unauthenticated', [], 401);
        }

        $product = Product::find($request->product_id);
        $variation = null;
        $price = $product->price_after_discount ?? $product->price;

        if ($request->variation_id) {
            $variation = Variation::where('id', $request->variation_id)
                                 ->where('product_id', $request->product_id)
                                 ->where('status', 1)
                                 ->first();

            if (!$variation) {
                return $this->error_response('Invalid variation for this product', []);
            }

            $price += $variation->price_adjustment;
        }

        $userId =  $user->id;

        // Get existing coupon if any
        $existingCart = Cart::where('user_id', $userId)->where('status', 1)->first();
        $couponId = $existingCart ? $existingCart->coupon_id : null;

        // Check if the same product with same variation already exists in cart
        $cart = Cart::where('user_id', $userId)
                    ->where('product_id', $request->product_id)
                    ->where('variation_id', $request->variation_id)
                    ->where('status', 1)
                    ->first();

        if ($cart) {
            // Update quantity and total
            $cart->quantity += $request->quantity;
            $cart->total_price_product = $cart->price * $cart->quantity;
            $cart->save();
        } else {
            // Create new cart item
            $cart = Cart::create([
                'product_id' => $product->id,
                'variation_id' => $request->variation_id,
                'user_id' => $userId,
                'quantity' => $request->quantity,
                'price' => $price,
                'total_price_product' => $price * $request->quantity,
                'coupon_id' => $couponId,
                'status' => 1
            ]);
        }

        return $this->success_response('Product added to cart', $cart);
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string'
        ]);

        $user = $request->user();
        
        if(!$user){
             return $this->error_response('Unauthenticated', [], 401);
        }

        // Get cart total
        $cartItems = Cart::where('user_id', $user->id)->where('status', 1)->get();
        
        if ($cartItems->isEmpty()) {
            return $this->error_response('Cart is empty', []);
        }

        $subtotal = $cartItems->sum('total_price_product');

        // Validate coupon
        $coupon = Coupon::where('code', $request->coupon_code)
            ->whereDate('expired_at', '>=', now())
            ->first();

        if (!$coupon) {
            return $this->error_response('Coupon not found or expired', []);
        }

        // Check if already used
        $alreadyUsed = UserCoupon::where('user_id', $user->id)
            ->where('coupon_id', $coupon->id)
            ->exists();

        if ($alreadyUsed) {
            return $this->error_response('Coupon already used', []);
        }

        // Check minimum total
        if ($subtotal < $coupon->minimum_total) {
            return $this->error_response("Minimum total is {$coupon->minimum_total}", []);
        }

        // Calculate percentage discount
        $discount = ($subtotal * $coupon->amount) / 100;

        // Apply to all cart items
        Cart::where('user_id', $user->id)
            ->where('status', 1)
            ->update([
                'coupon_id' => $coupon->id,
                'discount_coupon' => $discount
            ]);

        return $this->success_response('Coupon applied successfully', [
            'coupon_code' => $coupon->code,
            'percentage' => $coupon->amount,
            'discount_amount' => $discount,
            'total_before' => $subtotal,
            'total_after' => $subtotal - $discount
        ]);
    }

    public function removeCoupon(Request $request)
    {
        $user = $request->user();
        
        if(!$user){
             return $this->error_response('Unauthenticated', [], 401);
        }

        Cart::where('user_id', $user->id)
            ->where('status', 1)
            ->update([
                'coupon_id' => null,
                'discount_coupon' => null
            ]);

        return $this->success_response('Coupon removed successfully', []);
    }

    public function delete($id)
    {
        $cart = Cart::find($id);

        if (!$cart) {
            return $this->error_response('Cart item not found', []);
        }

        $cart->delete();

        return $this->success_response('Cart item deleted', []);
    }
}