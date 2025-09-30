<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get locale from URL parameter, session, or default
        $locale = $request->get('locale', Session::get('locale', config('app.locale')));

        // Validate locale
        if (! in_array($locale, array_keys(config('app.available_locales')))) {
            $locale = config('app.locale');
        }

        // Set application locale
        App::setLocale($locale);

        // Store locale in session
        Session::put('locale', $locale);

        return $next($request);
    }
}
