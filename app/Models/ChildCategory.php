<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildCategory extends Model
{
    use HasFactory;

    protected $fillable = ['subcategory_id', 'name', 'slug', 'image', 'status'];

    public function subcategory() {
        return $this->belongsTo(Subcategory::class);
    }

    public function subchildCategories() {
        return $this->hasMany(SubchildCategory::class);
    }

    public function products() {
        return $this->hasMany(Product::class);
    }
}
