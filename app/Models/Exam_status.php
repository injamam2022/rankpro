<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam_status extends Model
{

    protected $fillable = [
        'exam_id',
        'user_id',
        'type',
        'status',
        'created_at',
        'updated_at',
    ];
}
