<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\BannerCelebrity;
use App\Models\Celebrity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerCelebrityController extends Controller
{
    public function index()
    {
        $bannerCelebrities = BannerCelebrity::with('celebrity')->paginate(10);
        return view('admin.banner_celebrities.index', compact('bannerCelebrities'));
    }

    public function create()
    {
        $celebrities = Celebrity::all();
        return view('admin.banner_celebrities.create', compact('celebrities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'celebrity_id' => 'nullable|exists:celebrities,id'
        ]);

        $photoPath = uploadImage('assets/admin/uploads', $request->file('photo'));

        BannerCelebrity::create([
            'photo' => $photoPath,
            'celebrity_id' => $request->celebrity_id
        ]);

        return redirect()->route('banner-celebrities.index')
            ->with('success', __('messages.banner_celebrity_created'));
    }

    public function edit(BannerCelebrity $bannerCelebrity)
    {
        $celebrities = Celebrity::all();
        return view('admin.banner_celebrities.edit', compact('bannerCelebrity', 'celebrities'));
    }

    public function update(Request $request, BannerCelebrity $bannerCelebrity)
    {
        $request->validate([
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'celebrity_id' => 'nullable|exists:celebrities,id'
        ]);

        $data = ['celebrity_id' => $request->celebrity_id];

        if ($request->hasFile('photo')) {
          
            $data['photo'] = uploadImage('assets/admin/uploads', $request->file('photo'));
        }

        $bannerCelebrity->update($data);

        return redirect()->route('banner-celebrities.index')
            ->with('success', __('messages.banner_celebrity_updated'));
    }

    public function destroy(BannerCelebrity $bannerCelebrity)
    {
       
        $bannerCelebrity->delete();

        return redirect()->route('banner-celebrities.index')
            ->with('success', __('messages.banner_celebrity_deleted'));
    }
}