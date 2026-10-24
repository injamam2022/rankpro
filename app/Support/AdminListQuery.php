<?php

namespace App\Support;

use App\Models\Chapter;
use App\Models\Subject;
use App\Models\Topic;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class AdminListQuery
{
    public static function perPage(Request $request, int $default = 10): int
    {
        return 10;
    }

    public static function q(Request $request): string
    {
        return trim((string) $request->input('q', ''));
    }

    public static function like(string $q): string
    {
        return '%' . addcslashes($q, '%_\\') . '%';
    }

    public static function applySearch($query, string $q, array $columns)
    {
        if ($q === '') {
            return $query;
        }
        $like = self::like($q);
        return $query->where(function ($w) use ($columns, $like) {
            foreach ($columns as $i => $col) {
                if ($i === 0) {
                    $w->where($col, 'like', $like);
                } else {
                    $w->orWhere($col, 'like', $like);
                }
            }
        });
    }

    public static function when($value): string
    {
        if (empty($value)) {
            return '—';
        }
        try {
            return Carbon::parse($value)->format('d M Y, h:i A');
        } catch (\Throwable $e) {
            return '—';
        }
    }

    public static function id(Request $request, string $key): ?int
    {
        $value = $request->input($key);
        if ($value === null || $value === '') {
            return null;
        }
        $id = (int) $value;
        return $id > 0 ? $id : null;
    }

    public static function taxonomyOptions(Request $request, array $need = ['subject']): array
    {
        $subjectId = self::id($request, 'subject_id');
        $chapterId = self::id($request, 'chapter_id');
        $topicId = self::id($request, 'topic_id');

        if (session()->get('admmin_is_super') == 'T') {
            $forced = (int) session()->get('admmin_subject_id');
            if ($forced > 0) {
                $subjectId = $forced;
            }
        }

        $out = [
            'subject_id' => $subjectId,
            'chapter_id' => $chapterId,
            'topic_id' => $topicId,
            'subject_list' => collect(),
            'chapter_list' => collect(),
            'topic_list' => collect(),
        ];

        if (in_array('subject', $need, true)) {
            $subjects = Subject::where('status', 1);
            if (Schema::hasColumn('subjects', 'is_deleted')) {
                $subjects->where('is_deleted', 0);
            }
            if (session()->get('admmin_is_super') == 'T' && $subjectId) {
                $subjects->where('id', $subjectId);
            }
            $out['subject_list'] = $subjects->orderBy('name')->get(['id', 'name']);
        }

        if (in_array('chapter', $need, true) && $subjectId) {
            $chapters = Chapter::where('subject_id', $subjectId)->where('status', 1);
            if (Schema::hasColumn('chapters', 'class_level')) {
                $chapters->orderBy('class_level');
            }
            $out['chapter_list'] = $chapters->orderBy('name')->get();
        }

        if (in_array('topic', $need, true) && $chapterId) {
            $out['topic_list'] = Topic::where('chapter_id', $chapterId)
                ->where('status', 1)
                ->orderBy('name')
                ->get(['id', 'name']);
        }

        return $out;
    }
}
