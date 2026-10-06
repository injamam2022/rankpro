<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    /** Assigned tests stay in the student portal for this many days after the start date. */
    public const PORTAL_VISIBLE_DAYS = 7;

    public function scopeAssignedTo($query, $userId)
    {
        return $query->where(function ($query) use ($userId) {
            $query->whereExists(function ($assignment) use ($userId) {
                $assignment->selectRaw('1')->from('exam_assignments')
                    ->whereColumn('exam_assignments.exam_id', 'exams.id')
                    ->where('exam_assignments.user_id', $userId);
            })->orWhereExists(function ($batch) use ($userId) {
                $batch->selectRaw('1')->from('batch_exam')
                    ->join('batch_user', 'batch_user.batch_id', '=', 'batch_exam.batch_id')
                    ->join('batches', 'batches.id', '=', 'batch_exam.batch_id')
                    ->whereColumn('batch_exam.exam_id', 'exams.id')
                    ->where('batch_user.user_id', $userId)
                    ->where('batches.status', 1);
            });
        });
    }

    /**
     * Tests the student should see until the scheduled end.
     * Tests saved before an end time existed stay visible for PORTAL_VISIBLE_DAYS after the start date.
     */
    public function scopeListedForStudentPortal($query)
    {
        $now = now();
        $today = $now->toDateString();
        $grace = $now->copy()->subDays(self::PORTAL_VISIBLE_DAYS)->toDateString();
        $nowString = $now->format('Y-m-d H:i:s');
        $endCompare = $query->getConnection()->getDriverName() === 'sqlite'
            ? "datetime(exams.exam_end_date || ' ' || coalesce(exams.exam_end_time, '23:59:59')) >= datetime(?)"
            : "TIMESTAMP(exams.exam_end_date, COALESCE(exams.exam_end_time, '23:59:59')) >= ?";

        return $query->where(function ($ended) {
            $ended->where('exams.is_ended', 0)->orWhereNull('exams.is_ended');
        })->where(function ($query) use ($today, $grace, $nowString, $endCompare) {
            $query->where(function ($scheduled) use ($nowString, $endCompare) {
                $scheduled->whereNotNull('exams.exam_end_date')
                    ->whereRaw($endCompare, [$nowString]);
            })->orWhere(function ($open) use ($today, $grace) {
                $open->whereNull('exams.exam_end_date')
                    ->where(function ($window) use ($today, $grace) {
                        $window->where('exams.exam_date', '>=', $today)
                            ->orWhere('exams.exam_date', '>=', $grace);
                    });
            });
        });
    }

    public function scheduledStart()
    {
        if (empty($this->exam_date)) {
            return null;
        }

        $time = $this->exam_time ?: '00:00:00';

        try {
            return \Carbon\Carbon::parse(trim($this->exam_date.' '.$time));
        } catch (\Exception $e) {
            return null;
        }
    }

    public function scheduledEnd()
    {
        if (empty($this->exam_end_date)) {
            return null;
        }

        $time = $this->exam_end_time ?: '23:59:59';

        try {
            return \Carbon\Carbon::parse(trim($this->exam_end_date.' '.$time));
        } catch (\Exception $e) {
            return null;
        }
    }

    public function canBeStarted()
    {
        if ((int) $this->is_ended === 1) {
            return false;
        }

        $start = $this->scheduledStart();
        if ($start && now()->lt($start)) {
            return false;
        }

        $end = $this->scheduledEnd();
        if ($end && now()->gt($end)) {
            return false;
        }

        return true;
    }

    public function batches()
    {
        return $this->belongsToMany(Batch::class, 'batch_exam');
    }

    public function assignedStudents()
    {
        return $this->belongsToMany(User::class, 'exam_assignments');
    }

    protected $fillable = [
        'name','question_type','question_paper_id','no_of_question','totals_marks_for_exam',
        'total_time_for_exam','marks_per_question','time_per_question','exam_code',
        'negative_marking_applicable','negative_marking_per_question','exam_logo',
        'no_of_questions_per_subject','exam_instructions','description','type',
        'exam_date','exam_time','exam_end_date','exam_end_time','location_id','result_title','result_description', 
        'price', 'dis_price', 'tax','landing_icon','is_deleted','hard_level','medium_name','easy_level',
        'result_declaration','is_ended','ended_date','is_in_footer','is_trending','status',
        'is_proctored','proctoring_max_violations','created_at','updated_at'
    ];
}
