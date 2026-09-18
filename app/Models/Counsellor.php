<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Counsellor extends Model
{

    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'address',
        'profile_icon',
        'password',
        'code',
        'status',
        'created_at',
        'updated_at',
    ];
}
