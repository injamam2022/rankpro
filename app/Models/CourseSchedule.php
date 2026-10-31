<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseSchedule extends Model
{
    protected $fillable = [
        'section',
        'seq',
        'test_date',
        'test_type',
        'timing',
        'subjects',
        'status',
    ];

    protected $casts = [
        'test_date' => 'date',
        'subjects' => 'array',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
