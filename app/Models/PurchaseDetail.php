<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseDetail extends Model
{
    // use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'purchases_id', 'products_id', 'purchase_price', 'total', 'subtotal'
    ];
    protected $hidden = [];

    public function purchase()
    {
        return $this->hasOne(Purchase::class, 'id', 'purchases_id');
    }

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'products_id');
    }
}