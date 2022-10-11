<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Purchase extends Model
{
    // use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'suppliers_id', 'total_item', 'total_price', 'discount', 'paid'
    ];

    protected $hidden = [];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'suppliers_id', 'id');
    }
}