<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = [
        'name','question_type','question_paper_id','no_of_question','totals_marks_for_exam',
        'total_time_for_exam','marks_per_question','time_per_question','exam_code',
        'negative_marking_applicable','negative_marking_per_question','exam_logo',
        'no_of_questions_per_subject','exam_instructions','description','type',
        'exam_date','exam_time','location_id','result_title','result_description', 
        'price', 'dis_price', 'tax','landing_icon','is_deleted','hard_level','medium_name','easy_level',
        'result_declaration','is_ended','ended_date','is_in_footer','is_trending','status',
        'is_proctored','proctoring_max_violations','created_at','updated_at'
    ];
}
