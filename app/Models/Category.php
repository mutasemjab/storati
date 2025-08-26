<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schema;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Category extends Model
{
    use HasFactory, LogsActivity;
    
    protected $guarded = [];
    protected $appends = ['name']; // Include in JSON output

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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll() // Log all attributes since you're using $guarded = []
            ->logOnlyDirty() // Only log changed attributes
            ->dontSubmitEmptyLogs() // Don't log if nothing changed
            ->dontLogIfAttributesChangedOnly(['updated_at']) // Don't log if only updated_at changed
            ->setDescriptionForEvent(fn(string $eventName) => "Category {$eventName}")
            ->useLogName('category'); // Custom log name for filtering
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function getNameAttribute()
    {
        $lang = request()->header('Accept-Language') ?? App::getLocale();

        return $lang === 'ar' ? $this->name_ar : $this->name_en;
    }

     public function children()
    {
        return $this->hasMany(Category::class, 'category_id');
    }
}