<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class ValidateCsrfToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // dd($request);
        
        $csrfToken = $request->query('_token');

        // Validate the CSRF token
        if (!Session::token() || $csrfToken !== Session::token()) {
            abort(419, 'Page Expired');
        }

        // Authenticate the user
        $user = Auth::user();
        if (!$user) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
