<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    // use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'code_member', 'name', 'address', 'phone_number'
    ];

    protected $hidden = [];
}