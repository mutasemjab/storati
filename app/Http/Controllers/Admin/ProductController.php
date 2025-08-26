<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Celebrity;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Variation;
use App\Models\Shop;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{

    public function index()
    {
        $products = Product::with(['category', 'celebrity', 'brand', 'shop', 'images'])
                          ->latest()
                          ->paginate(15);
        
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        $celebrities = Celebrity::all();
        $brands = Brand::all();
        $shops = Shop::all();
        $colors = Color::all();
        $sizes = Size::all();
        
        return view('admin.products.create', compact('categories', 'celebrities', 'brands', 'shops', 'colors', 'sizes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'description_en' => 'required|string',
            'description_ar' => 'required|string',
            'price' => 'required|numeric|min:0',
            'tax' => 'numeric|min:0|max:100',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'category_id' => 'nullable|exists:categories,id',
            'celebrity_id' => 'nullable|exists:celebrities,id',
            'brand_id' => 'nullable|exists:brands,id',
            'shop_id' => 'nullable|exists:shops,id',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif',
            'gender' => 'required|in:man,woman,both',
            'variations.*.color_id' => 'required_with:variations|exists:colors,id',
            'variations.*.size_id' => 'required_with:variations|exists:sizes,id',
            'variations.*.price_adjustment' => 'nullable|numeric',
            'variations.*.status' => 'required_with:variations|in:1,2',
        ]);

        try {
            DB::beginTransaction();

            $data = $request->only([
                'name_en', 'name_ar', 'description_en', 'description_ar', 
                'price', 'tax', 'discount_percentage', 'category_id', 
                'celebrity_id', 'brand_id', 'shop_id','gender'
            ]);

            // Calculate price after discount
            if ($request->discount_percentage) {
                $data['price_after_discount'] = $data['price'] - ($data['price'] * $data['discount_percentage'] / 100);
            }

            $product = Product::create($data);

            // Handle image uploads
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path =  uploadImage('assets/admin/uploads', $image);
                    ProductImage::create([
                        'product_id' => $product->id,
                        'photo' => $path
                    ]);
                }
            }

            // Handle variations
            if ($request->has('variations')) {
                foreach ($request->variations as $variation) {
                    Variation::create([
                        'product_id' => $product->id,
                        'color_id' => $variation['color_id'],
                        'size_id' => $variation['size_id'],
                        'price_adjustment' => $variation['price_adjustment'] ?? 0,
                        'status' => $variation['status'] ?? 1,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('products.index')
                           ->with('success', __('messages.Product_Added_Successfully'));

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                           ->with('error', __('messages.Something_Went_Wrong'))
                           ->withInput();
        }
    }

    public function show(Product $product)
    {
        $product->load(['category', 'celebrity', 'brand', 'shop', 'images', 'variations.color', 'variations.size']);
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $celebrities = Celebrity::all();
        $brands = Brand::all();
        $shops = Shop::all();
        $colors = Color::all();
        $sizes = Size::all();
        
        $product->load(['images', 'variations']);
        
        return view('admin.products.edit', compact('product', 'categories', 'celebrities', 'brands', 'shops', 'colors', 'sizes'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'description_en' => 'required|string',
            'description_ar' => 'required|string',
            'price' => 'required|numeric|min:0',
            'tax' => 'numeric|min:0|max:100',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'category_id' => 'nullable|exists:categories,id',
            'celebrity_id' => 'nullable|exists:celebrities,id',
            'brand_id' => 'nullable|exists:brands,id',
            'shop_id' => 'nullable|exists:shops,id',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif',
            'gender' => 'required|in:man,woman,both',
            'variations.*.color_id' => 'required_with:variations|exists:colors,id',
            'variations.*.size_id' => 'required_with:variations|exists:sizes,id',
            'variations.*.price_adjustment' => 'nullable|numeric',
            'variations.*.status' => 'required_with:variations|in:1,2',
        ]);

        try {
            DB::beginTransaction();

            $data = $request->only([
                'name_en', 'name_ar', 'description_en', 'description_ar', 
                'price', 'tax', 'discount_percentage', 'category_id', 
                'celebrity_id', 'brand_id', 'shop_id','gender'
            ]);

            // Calculate price after discount
            if ($request->discount_percentage) {
                $data['price_after_discount'] = $data['price'] - ($data['price'] * $data['discount_percentage'] / 100);
            } else {
                $data['price_after_discount'] = null;
            }

            $product->update($data);

            // Handle new images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path =  uploadImage('assets/admin/uploads', $image);
                    ProductImage::create([
                        'product_id' => $product->id,
                        'photo' => $path
                    ]);
                }
            }

            // Handle image deletions
            if ($request->has('delete_images')) {
                foreach ($request->delete_images as $imageId) {
                    $image = ProductImage::find($imageId);
                    if ($image && $image->product_id == $product->id) {
                        $filePath = base_path('assets/admin/uploads/' . $image);
                        if (file_exists($filePath)) {
                            unlink($filePath);
                        }
                        $image->delete();
                    }
                }
            }

            // Update variations - delete existing and create new ones
            $product->variations()->delete();
            if ($request->has('variations')) {
                foreach ($request->variations as $variation) {
                    Variation::create([
                        'product_id' => $product->id,
                        'color_id' => $variation['color_id'],
                        'size_id' => $variation['size_id'],
                        'price_adjustment' => $variation['price_adjustment'] ?? 0,
                        'status' => $variation['status'] ?? 1,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('products.index')
                           ->with('success', __('messages.Product_Updated_Successfully'));

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                           ->with('error', __('messages.Something_Went_Wrong'))
                           ->withInput();
        }
    }

    public function destroy(Product $product)
    {
        try {
            // Delete associated images from storage
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->photo);
            }
            
            $product->delete();
            
            return redirect()->route('products.index')
                           ->with('success', __('messages.Product_Deleted_Successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', __('messages.Something_Went_Wrong'));
        }
    }

}