<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notice_user extends Model
{
    protected $fillable = [
        'notice_id','user_id','is_favorite','is_archive','is_urgent','is_deleted',
        'status','created_at','updated_at'
    ];
}
