<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminUserAccess extends Model
{
    protected $fillable = [
        'name','type','parent_id',
        'is_add','is_edit','is_list',
        'is_delete','is_export','order',
        'status','created_at','updated_at'
    ];
}
