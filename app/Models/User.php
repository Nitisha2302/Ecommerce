<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    // Add the fields that are mass assignable
    protected $fillable = [
       'first_name',
        'last_name',
        'email',
        'phone_number',
        'password',
        'apple_token',
        'facebook_token',
        'google_token',
        'is_social',
        'device_type',
        'device_id',
        'fcm_token',
        'latitude',
        'longitude',
        'api_token',
        'timestamp', // Ensure that this is camel case if needed, like 'created_at'
    ];

    // Relationships
    public function addresses() {
        return $this->hasMany(Address::class);
    }

    public function orders() {
        return $this->hasMany(Order::class);
    }

    public function cartItems() {
        return $this->hasMany(CartItem::class);
    }

    public function wishlists() {
        return $this->hasMany(Wishlist::class);
    }

    public function reviews() {
        return $this->hasMany(Review::class);
    }

  
}
