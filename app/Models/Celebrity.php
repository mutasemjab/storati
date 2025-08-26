<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Celebrity extends Model
{
    use HasFactory;
     protected $guarded = [];

    protected static function booted()
    {
        // Check if this model's table has a gender column
        static::addGlobalScope('gender', function ($builder) {
            if (!Schema::hasColumn((new static)->getTable(), 'gender')) {
                return;
            }

            $gender = request()->header('Gender');
            
            if (!$gender || !in_array($gender, ['man', 'woman'])) {
                return;
            }

            $builder->where(function($query) use ($gender) {
                $query->where('gender', $gender)
                      ->orWhere('gender', 'both');
            });
        });
    }

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
