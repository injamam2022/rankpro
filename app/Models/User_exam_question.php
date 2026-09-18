<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{

    protected $fillable = [
        'first_name',
        'last_name',
        'parent_first_name',
        'parent_last_name',
        'relation',
        'student_mobile_number',
        'password',
        'parent_mobile_number',
        'email_id',
        'profile_img',
        'coupon_code',
        'package',
        'is_lead',
        'verification_code',
        'access_token',
        'is_deleted',
        'status',
        'created_at',
        'updated_at',
    ];
}
