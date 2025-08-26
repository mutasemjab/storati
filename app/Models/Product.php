<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Product extends Model
{
    use HasFactory, LogsActivity;

    protected $guarded = [];
    protected $appends = ['name', 'description', 'is_favourite'];

    protected $casts = [
        'price' => 'decimal:2',
        'tax' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'price_after_discount' => 'decimal:2',
    ];

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
            ->setDescriptionForEvent(fn(string $eventName) => "Products {$eventName}")
            ->useLogName('products'); // Custom log name for filtering
    }


    public function getNameAttribute()
    {
        $lang = request()->header('Accept-Language') ?? App::getLocale();
        return $lang === 'ar' ? $this->name_ar : $this->name_en;
    }

    public function getDescriptionAttribute()
    {
        $lang = request()->header('Accept-Language') ?? App::getLocale();
        return $lang === 'ar' ? $this->description_ar : $this->description_en;
    }

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function celebrity()
    {
        return $this->belongsTo(Celebrity::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function variations()
    {
        return $this->hasMany(Variation::class);
    }


    public function ratings()
    {
        return $this->hasMany(ProductRating::class);
    }

    public function colors()
    {
        return $this->variations()->with('color')->get()->pluck('color')->unique('id');
    }

    public function sizes()
    {
        return $this->variations()->with('size')->get()->pluck('size')->unique('id');
    }

    public function getIsFavouriteAttribute()
    {
        if (!auth()->check()) {
            return 0;
        }

        return DB::table('product_favourites')
            ->where('product_id', $this->id)
            ->where('user_id', auth()->id())
            ->exists() ? 1 : 0;
    }

    // Add the relationship in Product model if not already exists
    public function favouritedBy()
    {
        return $this->belongsToMany(User::class, 'product_favourites', 'product_id', 'user_id');
    }
}
