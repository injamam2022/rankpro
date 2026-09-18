<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question_paper_question extends Model
{

    protected $fillable = [
        'question_number',
        'question_id',
        'question_paper_id',
        'question_paper_subject_id',
        'status',
        'created_at',
        'updated_at',
    ];
}
