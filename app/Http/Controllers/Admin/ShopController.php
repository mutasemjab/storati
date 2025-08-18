<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class ShopController extends Controller
{
    

    public function index()
    {
        $shops = Shop::withCount('products')->latest()->paginate(15);
        return view('admin.shops.index', compact('shops'));
    }

    public function create()
    {
        return view('admin.shops.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $data = $request->only(['name_en', 'name_ar']);

            // Upload photo using your custom uploadImage function
            if ($request->hasFile('photo')) {
                $data['photo'] = uploadImage('assets/admin/uploads/', $request->file('photo'));
            }

            Shop::create($data);

            DB::commit();

            return redirect()->route('shops.index')
                           ->with('success', __('messages.Shop_Added_Successfully'));

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                           ->with('error', __('messages.Something_Went_Wrong'))
                           ->withInput();
        }
    }

    public function show(Shop $shop)
    {
        $shop->load(['products.images', 'products.category']);
        return view('admin.shops.show', compact('shop'));
    }

    public function edit(Shop $shop)
    {
        return view('admin.shops.edit', compact('shop'));
    }

    public function update(Request $request, Shop $shop)
    {
        $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $data = $request->only(['name_en', 'name_ar']);

            // Handle photo update using your custom uploadImage function
            if ($request->hasFile('photo')) {
                // Delete old photo
                if ($shop->photo && File::exists(base_path('assets/admin/uploads/'.$shop->photo))) {
                    File::delete(base_path('assets/admin/uploads/'.$shop->photo));
                }
                
                // Upload new photo
                $data['photo'] = uploadImage('assets/admin/uploads/', $request->file('photo'));
            }

            $shop->update($data);

            DB::commit();

            return redirect()->route('shops.index')
                           ->with('success', __('messages.Shop_Updated_Successfully'));

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                           ->with('error', __('messages.Something_Went_Wrong'))
                           ->withInput();
        }
    }

   
}