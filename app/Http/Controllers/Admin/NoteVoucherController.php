<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\NoteVoucher;
use App\Models\VoucherProduct;
use App\Models\Warehouse;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Variation;


class NoteVoucherController extends Controller
{
   
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $noteVouchers = NoteVoucher::with(['warehouse', 'order', 'voucherProducts'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.note_vouchers.index', compact('noteVouchers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $warehouses = Warehouse::all();
        $orders = Order::all();
        $products = Product::all();
        $variations = Variation::all();
       $nextNumber = (NoteVoucher::max('id') ?? 0) + 1;
        return view('admin.note_vouchers.create', compact('nextNumber','warehouses', 'orders', 'products', 'variations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'number' => 'required|integer|unique:note_vouchers',
            'type' => 'required|integer|in:1,2',
            'date_note_voucher' => 'required|date',
            'note' => 'nullable|string',
            'warehouse_id' => 'nullable|exists:warehouses,id',
            'order_id' => 'nullable|exists:orders,id',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.variation_id' => 'nullable|exists:variations,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.note' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {
            $noteVoucher = NoteVoucher::create([
                'number' => $request->number,
                'type' => $request->type,
                'date_note_voucher' => $request->date_note_voucher,
                'note' => $request->note,
                'warehouse_id' => $request->warehouse_id,
                'order_id' => $request->order_id,
            ]);

            foreach ($request->products as $product) {
                VoucherProduct::create([
                    'note_voucher_id' => $noteVoucher->id,
                    'product_id' => $product['product_id'],
                    'variation_id' => $product['variation_id'] ?? null,
                    'quantity' => $product['quantity'],
                    'note' => $product['note'] ?? null,
                ]);
            }
        });

        return redirect()->route('note-vouchers.index')
            ->with('success', __('messages.Note_Voucher_Created_Successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(NoteVoucher $noteVoucher)
    {
        $noteVoucher->load(['warehouse', 'order', 'voucherProducts.product', 'voucherProducts.variation']);
        
        return view('admin.note_vouchers.show', compact('noteVoucher'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(NoteVoucher $noteVoucher)
    {
        $noteVoucher->load('voucherProducts');
        $warehouses = Warehouse::all();
        $orders = Order::all();
        $products = Product::all();
        $variations = Variation::all();

        return view('admin.note_vouchers.edit', compact('noteVoucher', 'warehouses', 'orders', 'products', 'variations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, NoteVoucher $noteVoucher)
    {
        $request->validate([
            'number' => 'required|integer|unique:note_vouchers,number,' . $noteVoucher->id,
            'type' => 'required|integer|in:1,2',
            'date_note_voucher' => 'required|date',
            'note' => 'nullable|string',
            'warehouse_id' => 'nullable|exists:warehouses,id',
            'order_id' => 'nullable|exists:orders,id',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.variation_id' => 'nullable|exists:variations,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.note' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $noteVoucher) {
            $noteVoucher->update([
                'number' => $request->number,
                'type' => $request->type,
                'date_note_voucher' => $request->date_note_voucher,
                'note' => $request->note,
                'warehouse_id' => $request->warehouse_id,
                'order_id' => $request->order_id,
            ]);

            // Delete existing voucher products
            $noteVoucher->voucherProducts()->delete();

            // Create new voucher products
            foreach ($request->products as $product) {
                VoucherProduct::create([
                    'note_voucher_id' => $noteVoucher->id,
                    'product_id' => $product['product_id'],
                    'variation_id' => $product['variation_id'] ?? null,
                    'quantity' => $product['quantity'],
                    'note' => $product['note'] ?? null,
                ]);
            }
        });

        return redirect()->route('note-vouchers.index')
            ->with('success', __('messages.Note_Voucher_Updated_Successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NoteVoucher $noteVoucher)
    {
        $noteVoucher->delete();

        return redirect()->route('note-vouchers.index')
            ->with('success', __('messages.Note_Voucher_Deleted_Successfully'));
    }
}