<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User_detail extends Model
{

    protected $fillable = [
        'user_id',
        'strong_subject',
        'strong_chapter',
        'strong_topic',
        'improve_subject',
        'improve_chapter',
        'improve_topic',
        'work_hard_subject',
        'work_hard_chapter',
        'work_hard_topic',
        'status',
        'created_at',
        'updated_at',
    ];
}
