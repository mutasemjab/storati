<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BannerCelebrity extends Model
{
    use HasFactory;


    protected $fillable = ['photo', 'celebrity_id'];

    public function celebrity()
    {
        return $this->belongsTo(Celebrity::class);
    }
}
