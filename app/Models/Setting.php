<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Setting extends Model
{
    // use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'company_name', 'address', 'phone_number', 'type_nota', 'discount', 'path_logo', 'path_member_card'
    ];
    protected $hidden = [];
}