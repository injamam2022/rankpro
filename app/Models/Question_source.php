<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question_source extends Model
{
    protected $fillable = [
        'name',
        'status',
        'created_at',
        'updated_at',
    ];

    public function questions()
    {
        return $this->belongsToMany(
            Question::class,
            'question_question_source',
            'question_source_id',
            'question_id'
        );
    }
}
