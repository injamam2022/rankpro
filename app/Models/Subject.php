<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{

    protected $fillable = [
        'name',
        'per_question_time',
        'per_exam_no_of_question',
        'is_deleted',
        'status',
        'created_at',
        'updated_at',
    ];
}
