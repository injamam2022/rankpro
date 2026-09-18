<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User_apply extends Model
{

    protected $fillable = [
        'first_name',
        'last_name',
        'mobile_number',
        'is_whatsapp',
        'email_id',
        'profile_img',
        'password',
        'father_full_name',
        'father_mobile_number',
        'otp',
        'status',
        'created_at',
        'updated_at',
    ];
}
