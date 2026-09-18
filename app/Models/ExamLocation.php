<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamLocation extends Model
{

    protected $fillable = [
        'exam_id',
        'location_id',
        'status',
        'created_at',
        'updated_at',
    ];
}
