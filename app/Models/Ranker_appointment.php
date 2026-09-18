<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ranker_appointment extends Model
{
    protected $fillable = [
        'student_id','ranker_id','date','start_time',
        'end_time','link','description',
        'status','created_at','updated_at'
    ];
}
