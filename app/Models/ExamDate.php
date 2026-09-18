<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamDate extends Model
{

    protected $fillable = [
        'exam_id',
        'date_name',
        'status',
        'created_at',
        'updated_at',
    ];
}
