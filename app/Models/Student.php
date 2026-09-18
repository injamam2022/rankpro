<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'name','email','password','phone_number',
        'profile_icon','college_name','location','access_token',
        'hash_code','status','created_at','updated_at'
    ];
}
