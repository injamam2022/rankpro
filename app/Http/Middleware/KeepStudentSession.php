<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * ICICI posts the browser back from their site. That request does not include
 * the student session. Starting a fresh session here would replace the login cookie.
 */
class KeepStudentSession
{
    public function handle(Request $request, Closure $next): Response
    {
        $cookieName = (string) config('session.cookie');
        $hadSession = $cookieName !== '' && $request->cookies->has($cookieName);

        $response = $next($request);

        if ($hadSession || !$request->is('payment/icici/return')) {
            return $response;
        }

        foreach ($response->headers->getCookies() as $cookie) {
            if ($cookie->getName() === $cookieName) {
                $response->headers->removeCookie($cookie->getName(), $cookie->getPath(), $cookie->getDomain());
            }
        }

        return $response;
    }
}
