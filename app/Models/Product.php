<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'subcategory_id', 'child_category_id', 'subchild_category_id',
        'brand_id', 'name', 'slug', 'description', 'price', 'discount_price',
        'stock', 'status', 'images'
    ];

    protected $casts = [
        'images' => 'array', // stored as JSON
    ];

    public function category() { return $this->belongsTo(Category::class); }
    public function subcategory() { return $this->belongsTo(Subcategory::class); }
    public function childCategory() { return $this->belongsTo(ChildCategory::class); }
    public function subchildCategory() { return $this->belongsTo(SubchildCategory::class); }
    public function brand() { return $this->belongsTo(Brand::class); }

    public function reviews() { return $this->hasMany(Review::class); }
    public function cartItems() { return $this->hasMany(CartItem::class); }
    public function wishlistItems() { return $this->hasMany(Wishlist::class); }
}
