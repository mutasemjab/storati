<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Shop extends Model
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

}
