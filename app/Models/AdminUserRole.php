<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminUserRole extends Model
{
    protected $fillable = [
        'name','description','status','created_at','updated_at'
    ];
}
