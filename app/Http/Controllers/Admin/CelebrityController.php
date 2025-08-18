<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Celebrity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class CelebrityController extends Controller
{
   

    public function index()
    {
        $celebrities = Celebrity::latest()->paginate(15);
        return view('admin.celebrities.index', compact('celebrities'));
    }

    public function create()
    {
        return view('admin.celebrities.create');
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
                $data['photo'] = uploadImage('assets/admin/uploads', $request->file('photo'));
            }

            Celebrity::create($data);

            DB::commit();

            return redirect()->route('celebrities.index')
                           ->with('success', __('messages.Celebrity_Added_Successfully'));

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                           ->with('error', __('messages.Something_Went_Wrong'))
                           ->withInput();
        }
    }

    public function show(Celebrity $celebrity)
    {
        return view('admin.celebrities.show', compact('celebrity'));
    }

    public function edit(Celebrity $celebrity)
    {
        return view('admin.celebrities.edit', compact('celebrity'));
    }

    public function update(Request $request, Celebrity $celebrity)
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
                if ($celebrity->photo && File::exists(public_path($celebrity->photo))) {
                    File::delete(base_path('assets/admin/uploads/'.$celebrity->photo));
                }
                
                // Upload new photo
                $data['photo'] = uploadImage('assets/admin/uploads/', $request->file('photo'));
            }

            $celebrity->update($data);

            DB::commit();

            return redirect()->route('celebrities.index')
                           ->with('success', __('messages.Celebrity_Updated_Successfully'));

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                           ->with('error', __('messages.Something_Went_Wrong'))
                           ->withInput();
        }
    }

    public function destroy(Celebrity $celebrity)
    {
        try {
            // Check if celebrity is used in products
            if ($celebrity->products()->count() > 0) {
                return redirect()->back()
                               ->with('error', __('messages.Cannot_Delete_Celebrity_Has_Products'));
            }

            // Delete photo from filesystem
            if ($celebrity->photo && File::exists(public_path($celebrity->photo))) {
                File::delete(public_path($celebrity->photo));
            }
            
            $celebrity->delete();
            
            return redirect()->route('admin.celebrities.index')
                           ->with('success', __('messages.Celebrity_Deleted_Successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', __('messages.Something_Went_Wrong'));
        }
    }
}