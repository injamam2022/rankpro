<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Administrator extends Model
{
    protected $fillable = [
        'user_name','login_email','password',
        'type', 'profile_icon','phone_number',
        'admin_role_role_id','subject_id','last_login_ip',
        'last_login_time','hash_token',
        'status','created','updated'
    ];
}
