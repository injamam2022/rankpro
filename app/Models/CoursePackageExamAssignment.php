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

    /** Paper can be started by purchasers right now. */
    public function isAvailableNow(?Carbon $at = null): bool
    {
        $at = $at ?: now();

        if ($this->available_from && $at->lt($this->available_from)) {
            return false;
        }

        if ($this->available_until && $at->gt($this->available_until)) {
            return false;
        }

        return true;
    }

    /** upcoming | available | expired */
    public function windowStatus(?Carbon $at = null): string
    {
        $at = $at ?: now();

        if ($this->available_from && $at->lt($this->available_from)) {
            return 'upcoming';
        }

        if ($this->available_until && $at->gt($this->available_until)) {
            return 'expired';
        }

        return 'available';
    }

    public function availabilityLabel(): string
    {
        $from = optional($this->available_from)->format('d M Y, h:i A');
        $until = optional($this->available_until)->format('d M Y, h:i A');

        if ($from && $until) {
            return $from.' → '.$until;
        }
        if ($from) {
            return 'From '.$from;
        }
        if ($until) {
            return 'Until '.$until;
        }

        return 'Always open (no window set)';
    }
}
