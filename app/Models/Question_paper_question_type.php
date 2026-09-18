<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question_paper_question_type extends Model
{

    protected $fillable = [
        'question_paper_id',
        'question_type_id',
        'value',
        'status',
        'created_at',
        'updated_at',
    ];
}
