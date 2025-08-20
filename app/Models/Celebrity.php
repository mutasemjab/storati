<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Celebrity extends Model
{
    use HasFactory;
     protected $guarded = [];

      public function products()
    {
        return $this->hasMany(Product::class);
    }

      public function stories()
    {
        return $this->hasMany(CelebrityStory::class);
    }

    public function hasUnseenStoriesForUser($userId)
    {
        if (!$userId) return true; // For guest users, all stories are unseen
        
        return $this->activeStories()
                    ->whereDoesntHave('views', function($query) use ($userId) {
                        $query->where('user_id', $userId);
                    })
                    ->exists();
    }

    // Get latest story for this celebrity
    public function getLatestStoryAttribute()
    {
        return $this->activeStories()->first();
    }
    
    // Get active (non-expired) stories
    public function activeStories()
    {
        return $this->hasMany(CelebrityStory::class)->active()->orderBy('created_at', 'desc');
    }
}
