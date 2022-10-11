<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaleDetail extends Model
{
    // use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'sales_id', 'products_id', 'selling_price', 'total', 'discount', 'subtotal'
    ];

    protected $hidden = [];

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'products_id');
    }
}