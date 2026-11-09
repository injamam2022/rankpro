<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class CoursePackageExamAssignment extends Model
{
    protected $fillable = [
        'course_package_id',
        'course_schedule_id',
        'question_paper_id',
        'available_from',
        'available_until',
        'status',
    ];

    protected $casts = [
        'available_from' => 'datetime',
        'available_until' => 'datetime',
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

    /** Paper can be started by purchasers from the exam date onward. */
    public function isAvailableNow(?Carbon $at = null): bool
    {
        return $this->windowStatus($at) === 'available';
    }

    /** upcoming | available */
    public function windowStatus(?Carbon $at = null): string
    {
        $at = ($at ?: now())->copy()->startOfDay();
        $from = $this->examDate();

        if ($from && $at->lt($from)) {
            return 'upcoming';
        }

        return 'available';
    }

    public function examDate(): ?Carbon
    {
        if ($this->available_from) {
            return $this->available_from->copy()->startOfDay();
        }

        $scheduleDate = $this->schedule?->test_date;
        if ($scheduleDate) {
            return Carbon::parse($scheduleDate)->startOfDay();
        }

        return null;
    }

    public function availabilityLabel(): string
    {
        $from = optional($this->examDate())->format('d M Y');

        return $from ? 'Available from '.$from : 'Date not set';
    }
}
