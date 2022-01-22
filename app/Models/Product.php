<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['category_id', 'name_product', 'slug', 'price', 'stock', 'description', 'image', 'imagepath'];


    public function Category()
    {
        return $this->belongsTo(Category::class);
    }
}
