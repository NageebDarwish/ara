<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth;

class admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
     public function handle(Request $request, Closure $next,...$roles): Response
    {

        if (!auth()->check()) {
            abort(401, 'Unauthenticated');
        }

        // Check if user has any of the allowed roles
        if (in_array(auth()->user()->role, $roles)) {
            return $next($request);
        }

        Auth::logout();
        abort(403, 'Unauthorized');

    }
}
