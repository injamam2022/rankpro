<?php

namespace App\Support;

use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminListQuery
{
    public static function perPage(Request $request, int $default = 10): int
    {
        $n = (int) $request->input('per_page', $default);
        return in_array($n, [10, 25, 50], true) ? $n : $default;
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
}
