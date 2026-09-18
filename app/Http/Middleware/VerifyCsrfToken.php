<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        "leads/*",
        "update-user-exam-question",
        "update-time",
        "save-exam",
        "update-exam-time",
        "end-exam"
    ];
}
