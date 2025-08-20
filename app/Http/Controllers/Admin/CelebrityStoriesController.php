<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Celebrity;
use App\Models\CelebrityStory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class CelebrityStoriesController extends Controller
{
    public function index()
    {
        $celebrities = Celebrity::with(['activeStories' => function($query) {
            $query->orderBy('created_at', 'desc');
        }])->whereHas('activeStories')->get();

        return view('admin.celebrity-stories.index', compact('celebrities'));
    }

    public function show(Celebrity $celebrity, CelebrityStory $story)
    {
        // Increment views count
        $story->increment('views_count');
        
        $stories = $celebrity->activeStories()->get();
        $currentIndex = $stories->search(function($item) use ($story) {
            return $item->id === $story->id;
        });

        return view('admin.celebrity-stories.show', compact('celebrity', 'story', 'stories', 'currentIndex'));
    }

    public function create()
    {
        $celebrities = Celebrity::orderBy('name_en')->get();
        return view('admin.celebrity-stories.create', compact('celebrities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'celebrity_id' => 'required|exists:celebrities,id',
            'type' => 'required|in:photo,video',
            'media' => 'required|file|mimes:jpg,jpeg,png,gif,mp4,mov,avi|max:20480', // 20MB max
            'caption' => 'nullable|string|max:500'
        ]);

        $media = $request->file('media');
        $type = $request->type;
        
        $mediaPath =  uploadImage('assets/admin/uploads/', $media);
        
        $thumbnailPath = null;
        
        // Create thumbnail for videos
        if ($type === 'video') {
            // You might want to use FFmpeg or similar for video thumbnails
            // For now, we'll just set it as null
            // $thumbnailPath = $this->createVideoThumbnail($mediaPath);
        }

        CelebrityStory::create([
            'celebrity_id' => $request->celebrity_id,
            'type' => $type,
            'media_path' => $mediaPath,
            'thumbnail_path' => $thumbnailPath,
            'caption' => $request->caption,
            'is_active' => true
        ]);

        return redirect()->route('celebrity-stories.index')
                        ->with('success', __('messages.Story added successfully'));
    }

    public function destroy(CelebrityStory $story)
    {
        if ($story->media_path && File::exists(base_path('assets/admin/uploads/'.$story->media_path))) {
            File::delete(base_path('assets/admin/uploads/'.$story->media_path));
        }

        $story->delete();

        return redirect()->route('celebrity-stories.index')
                        ->with('success', __('messages.Story deleted successfully'));
    }

    public function toggleActive(CelebrityStory $story)
    {
        $story->update(['is_active' => !$story->is_active]);
        
        $message = $story->is_active 
            ? __('messages.Story activated successfully')
            : __('messages.Story deactivated successfully');
            
        return back()->with('success', $message);
    }

    private function createVideoThumbnail($videoPath)
    {
        // Implementation for creating video thumbnails
        // You can use FFmpeg or similar libraries
        // This is a placeholder - implement based on your needs
        return null;
    }
}