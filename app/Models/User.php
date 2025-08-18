<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class User extends Authenticatable
{
   use HasApiTokens, HasFactory, Notifiable, LogsActivity;

   protected $guarded = [];

   protected $hidden = [
      'remember_token',
   ];

   // Append the photo_url attribute to JSON responses
    protected $appends = ['photo_url'];
    
    // Add a custom accessor for the photo URL
    public function getPhotoUrlAttribute()
    {
        if ($this->photo) {
            // Use the APP_URL from the .env file
            $baseUrl = rtrim(config('app.url'), '/');
            return $baseUrl . '/assets/admin/uploads/' . $this->photo;
        }
        
        return null;
    }

        public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll() // Log all attributes since you're using $guarded = []
            ->logOnlyDirty() // Only log changed attributes
            ->dontSubmitEmptyLogs() // Don't log if nothing changed
            ->dontLogIfAttributesChangedOnly(['updated_at']) // Don't log if only updated_at changed
            ->setDescriptionForEvent(fn(string $eventName) => "User {$eventName}")
            ->useLogName('User'); // Custom log name for filtering
    }
    
       public function coupons()
    {
        return $this->belongsToMany(Coupon::class, 'user_coupons')
                    ->withTimestamps(); 
    }

    public function favourites()
    {
        return $this->belongsToMany(Product::class, 'product_favourites', 'user_id', 'product_id')->with('images');
    }
  
    public function providerFavourites()
    {
        return $this->belongsToMany(ProviderType::class, 'provider_favourites', 'user_id', 'provider_type_id')->with('images');
    }

    public function pointsTransactions()
    {
        return $this->hasMany(PointTransaction::class);
    }


}
