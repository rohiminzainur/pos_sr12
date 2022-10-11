<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    // use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'members_id', 'users_id', 'total_item', 'total_price', 'discount', 'paid', 'received'
    ];

    protected $hidden = [];

    public function member()
    {
        return $this->hasOne(Member::class, 'id', 'members_id');
    }
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'users_id');
    }
}