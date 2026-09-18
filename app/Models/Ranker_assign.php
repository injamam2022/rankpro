<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ranker_assign extends Model
{
    protected $fillable = [
        'student_id','ranker_id','date','payment_id',
        'payment_amount','status','created_at','updated_at'
    ];
}
