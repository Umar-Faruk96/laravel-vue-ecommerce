<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\{RedirectResponse, Response};

class GuestOrVerified extends \Illuminate\Auth\Middleware\EnsureEmailIsVerified
{

    public function handle($request, Closure $next, $redirectToRoute = null): Response|RedirectResponse|null
    {
        if (!$request->user()) {
            return $next($request);
        }
        return parent::handle($request, $next, $redirectToRoute);
    }
}
