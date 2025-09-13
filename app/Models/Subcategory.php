<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'name', 'slug', 'image', 'status'];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function childCategories() {
        return $this->hasMany(ChildCategory::class);
    }

    public function products() {
        return $this->hasMany(Product::class);
    }
}
