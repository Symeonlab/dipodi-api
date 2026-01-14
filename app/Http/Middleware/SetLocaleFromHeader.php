<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocaleFromHeader
{
    public function handle(Request $request, Closure $next)
    {
        $locale = $request->header('Accept-Language');

        if ($locale) {
            // Basic check, e.g., 'fr', 'en', 'ar'
            $locale = substr($locale, 0, 2);
            if (in_array($locale, ['en', 'fr', 'ar'])) {
                App::setLocale($locale);
            }
        }

        return $next($request);
    }
}
