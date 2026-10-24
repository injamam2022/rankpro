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
}
