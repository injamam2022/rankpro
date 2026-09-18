<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamLanguage extends Model
{

    protected $fillable = [
        'exam_id',
        'language_id',
        'status',
        'created_at',
        'updated_at',
    ];
}
