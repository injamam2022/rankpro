<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoursePackageExamAssignment extends Model
{
    protected $fillable = [
        'course_package_id',
        'course_schedule_id',
        'question_paper_id',
        'status',
    ];

    public function package()
    {
        return $this->belongsTo(CoursePackage::class, 'course_package_id');
    }

    public function schedule()
    {
        return $this->belongsTo(CourseSchedule::class, 'course_schedule_id');
    }

    public function questionPaper()
    {
        return $this->belongsTo(Question_paper::class, 'question_paper_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
