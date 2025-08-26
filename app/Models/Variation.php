<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variation extends Model
{
    use HasFactory;

     protected $guarded = [];
     
     protected $casts = [
        'price_adjustment' => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }

     public function getFinalPriceAttribute()
    {
        $basePrice = $this->product->price_after_discount ?? $this->product->price;
        return $basePrice + $this->price_adjustment;
    }

}
