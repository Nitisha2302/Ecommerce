<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubchildCategory extends Model
{
    use HasFactory;

    protected $fillable = ['child_category_id', 'name', 'slug', 'image', 'status'];

    public function childCategory() {
        return $this->belongsTo(ChildCategory::class);
    }

    public function products() {
        return $this->hasMany(Product::class);
    }
}
