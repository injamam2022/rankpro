<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offline_exam_user extends Model
{

    protected $fillable = [
        'user_id',
        'exam_id',
        'total_answer',
        'total_right_answer',
        'total_number',
        'status',
        'created_at',
        'updated_at',
    ];
}
