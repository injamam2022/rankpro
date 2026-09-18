<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question_paper extends Model
{
    protected $fillable = [
        'name','hard_level','medium_level','easy_level',
        'no_of_question','marks_per_question','totals_marks_for_exam','time_per_question',
        'total_time_for_exam','negative_marking_applicable','negative_marking_per_question',
        'administrator_id','is_deleted','status','created_at','updated_at'
    ];

}
