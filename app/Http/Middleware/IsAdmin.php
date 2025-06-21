<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        logger('IsAdmin middleware called');
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }

        abort(403);
    }
}
