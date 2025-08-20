<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CelebrityStory extends Model
{
    use HasFactory;

    protected $guarded = [];

     protected $casts = [
        'expires_at' => 'datetime',
        'is_active' => 'boolean'
    ];

    protected static function boot()
    {
        parent::boot();
        
        // Auto-set expiration to 24 hours from now when creating
        static::creating(function ($story) {
            if (!$story->expires_at) {
                $story->expires_at = Carbon::now()->addHours(24);
            }
        });
    }

    public function celebrity()
    {
        return $this->belongsTo(Celebrity::class);
    }

     public function views()
    {
        return $this->hasMany(StoryView::class, 'story_id');
    }

    public function viewedByUsers()
    {
        return $this->belongsToMany(User::class, 'story_views', 'story_id', 'user_id')
                    ->withPivot('viewed_at')
                    ->withTimestamps();
    }

      public function isViewedByUser($userId)
    {
        return $this->views()->where('user_id', $userId)->exists();
    }
    

    // Check if story is still active and not expired
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where('expires_at', '>', Carbon::now());
    }

    // Check if story has expired
    public function isExpired()
    {
        return $this->expires_at && Carbon::now()->gt($this->expires_at);
    }

    // Get media URL
    public function getMediaUrlAttribute()
    {
        return asset('assets/admin/uploads/' . $this->media_path);
    }

    // Get thumbnail URL
    public function getThumbnailUrlAttribute()
    {
        if ($this->thumbnail_path) {
            return asset('assets/admin/uploads/' . $this->thumbnail_path);
        }
        return $this->type === 'photo' ? $this->media_url : null;
    }
}
