<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        // Get locale from request parameter
        $locale = $request->get('locale');

        // If locale is provided and supported, set it
        if ($locale && in_array($locale, config('app.supported_locales', ['fr']))) {
            dump('Setting locale from request parameter:', $locale);
            App::setLocale($locale);
            Session::put('locale', $locale);
        }
        // Otherwise, use session locale if available
        elseif (Session::has('locale') && in_array(Session::get('locale'), config('app.supported_locales', ['fr']))) {
            App::setLocale(Session::get('locale'));
            dump('Setting locale from session:', Session::get('locale'));
        }
        // Fall back to default locale
        else {
            dump('Setting default locale:', config('app.locale', 'fr'));
            App::setLocale(config('app.locale', 'fr'));
        }

        return $next($request);
    }
}
