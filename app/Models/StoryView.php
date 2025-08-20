<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoryView extends Model
{
    use HasFactory;
    
    protected $guarded = [];
     protected $casts = [
        'viewed_at' => 'datetime'
    ];

    protected static function boot()
    {
        parent::boot();
        
        // Auto-set viewed_at to current time when creating
        static::creating(function ($storyView) {
            if (!$storyView->viewed_at) {
                $storyView->viewed_at = Carbon::now();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function story()
    {
        return $this->belongsTo(CelebrityStory::class, 'story_id');
    }

    /**
     * Check if user has viewed a specific story
     */
    public static function hasUserViewedStory($userId, $storyId)
    {
        return self::where('user_id', $userId)
                   ->where('story_id', $storyId)
                   ->exists();
    }

    /**
     * Get all story IDs viewed by a user
     */
    public static function getViewedStoriesByUser($userId)
    {
        return self::where('user_id', $userId)
                   ->pluck('story_id')
                   ->toArray();
    }

    /**
     * Mark story as viewed by user
     */
    public static function markAsViewed($userId, $storyId)
    {
        return self::firstOrCreate([
            'user_id' => $userId,
            'story_id' => $storyId
        ], [
            'viewed_at' => Carbon::now()
        ]);
    }
}
