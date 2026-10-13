<?php

namespace App\Http\Middleware;

use App\Support\StudentScope;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RememberStudentScope
{
    public function handle(Request $request, Closure $next)
    {
        if (!$this->applies($request)) {
            return $next($request);
        }

        if ($request->isMethod('GET')) {
            if ($request->query->has('exam_type')) {
                session([StudentScope::SESSION_EXAM_TYPE => StudentScope::normalizeType($request->query('exam_type'))]);
            }
            if ($request->query->has('subject_id')) {
                session([StudentScope::SESSION_SUBJECT_ID => StudentScope::normalizeId($request->query('subject_id'))]);
            }
        }

        if (!$request->query->has('exam_type')) {
            $stored = session(StudentScope::SESSION_EXAM_TYPE);
            if ($stored !== null && $stored !== '') {
                $request->merge(['exam_type' => $stored]);
            }
        }

        if (!$request->query->has('subject_id')) {
            $stored = session(StudentScope::SESSION_SUBJECT_ID);
            if ($stored !== null && $stored !== '') {
                $request->merge(['subject_id' => $stored]);
            }
        }

        return $next($request);
    }

    protected function applies(Request $request): bool
    {
        if (!Auth::check()) {
            return false;
        }

        return !$request->is(
            'admin*',
            'counsellor*',
            'login*',
            'signup*',
            'logout*',
            'api*',
            'start-online-exam*',
            'online-exam*',
            'start-offline-exam*',
            'start-exam*'
        );
    }
}
