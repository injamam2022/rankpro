<?php

namespace App\Support;

class StudentScope
{
    public const SESSION_EXAM_TYPE = 'student_exam_type';
    public const SESSION_SUBJECT_ID = 'student_subject_id';

    public static function examType(): string
    {
        return self::normalizeType(request()->input('exam_type', session(self::SESSION_EXAM_TYPE, '')));
    }

    public static function subjectId(): string
    {
        return self::normalizeId(request()->input('subject_id', session(self::SESSION_SUBJECT_ID, '')));
    }

    public static function normalizeType($value): string
    {
        if ($value === null || $value === '' || $value === '0' || $value === 0 || (string) $value === '3') {
            return '';
        }

        $value = (string) $value;

        return in_array($value, ['1', '2'], true) ? $value : '';
    }

    public static function normalizeId($value): string
    {
        if ($value === null || $value === '' || $value === '0' || $value === 0) {
            return '';
        }

        return (string) $value;
    }

    public static function query(): array
    {
        $params = [];
        $type = self::examType();
        $subjectId = self::subjectId();

        if ($type !== '') {
            $params['exam_type'] = $type;
        }
        if ($subjectId !== '') {
            $params['subject_id'] = $subjectId;
        }

        return $params;
    }

    public static function filterUrl($examType, $subjectId, bool $includeExamType = true): string
    {
        $keep = request()->query();
        foreach (['page', 'exam_type', 'subject_id', 'mcq_page', 'strength_page', 'weakness_page', 'mistake_page', 'mm_page'] as $drop) {
            unset($keep[$drop]);
        }

        if ($includeExamType) {
            $keep['exam_type'] = self::normalizeType($examType);
        }
        $keep['subject_id'] = self::normalizeId($subjectId);

        $query = http_build_query($keep);

        return request()->url() . ($query !== '' ? '?' . $query : '');
    }

    public static function constrainOfficialAttempts($query, string $exams = 'exams', string $examUsers = 'exam_users', bool $includeSubject = true)
    {
        $query->where(function ($q) use ($exams) {
            $q->whereNull($exams.'.exam_code')
                ->orWhere($exams.'.exam_code', 'not like', 'CT-%');
        })->where(function ($q) use ($examUsers) {
            $q->whereNull($examUsers.'.exam_type')
                ->orWhere($examUsers.'.exam_type', '<>', 'CUSTOM');
        })->whereNotNull($examUsers.'.percentage');

        $type = self::examType();
        if ($type !== '') {
            $query->where($exams.'.type', $type);
        }

        $subjectId = $includeSubject ? self::subjectId() : '';
        if ($subjectId !== '') {
            $query->where(function ($outer) use ($subjectId, $exams) {
                $outer->where(function ($online) use ($subjectId, $exams) {
                    $online->whereNotNull($exams.'.question_paper_id')
                        ->whereExists(function ($sub) use ($subjectId, $exams) {
                            $sub->selectRaw('1')
                                ->from('question_paper_questions')
                                ->leftJoin('questions', 'questions.id', '=', 'question_paper_questions.question_id')
                                ->whereColumn('question_paper_questions.question_paper_id', $exams.'.question_paper_id')
                                ->where('questions.subject_id', $subjectId);
                        });
                })->orWhere(function ($offline) use ($subjectId, $exams) {
                    $offline->whereNull($exams.'.question_paper_id')
                        ->whereExists(function ($sub) use ($subjectId, $exams) {
                            $sub->selectRaw('1')
                                ->from('offline_exam_questions')
                                ->whereColumn('offline_exam_questions.exam_id', $exams.'.id')
                                ->where('offline_exam_questions.subject_id', $subjectId);
                        });
                });
            });
        }

        return $query;
    }

    public static function officialAttemptSql(string $exams = 'exams', string $examUsers = 'exam_users'): string
    {
        $sql = " AND ({$exams}.exam_code IS NULL OR {$exams}.exam_code NOT LIKE 'CT-%')"
            ." AND ({$examUsers}.exam_type IS NULL OR {$examUsers}.exam_type <> 'CUSTOM')"
            ." AND {$examUsers}.percentage IS NOT NULL";

        $type = self::examType();
        if ($type !== '') {
            $sql .= ' AND '.$exams.'.type = '.(int) $type;
        }

        return $sql;
    }
}
