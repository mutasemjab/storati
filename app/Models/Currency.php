<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $fillable = ['name', 'code', 'symbol', 'exchange_rate', 'is_default', 'is_active'];
 
    protected $casts = [
        'exchange_rate' => 'float',
        'is_default'    => 'boolean',
        'is_active'     => 'boolean',
    ];
 
    public static function getDefault(): self
    {
        return static::where('is_default', true)->firstOrFail();
    }
}
