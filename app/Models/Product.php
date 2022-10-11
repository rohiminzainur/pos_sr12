<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    // use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'categories_id', 'code_product', 'name', 'brand', 'slug', 'purchase_price', 'discount', 'selling_price', 'stock'
    ];

    protected $hidden = [];

    public function category()
    {
        return $this->belongsTo(Category::class, 'categories_id', 'id');
    }
}