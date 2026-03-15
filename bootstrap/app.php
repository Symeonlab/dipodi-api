<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Global middleware — ForceHttps for production
        $middleware->prepend([
            \App\Http\Middleware\ForceHttps::class,
        ]);

        // Trust all proxies (Docker / Sliplane / load balancer)
        $middleware->trustProxies(at: '*');

        // API middleware group — locale + app key verification
        $middleware->api(prepend: [
            \App\Http\Middleware\SetLocaleFromHeader::class,
            \App\Http\Middleware\VerifyAppKey::class,
        ]);

        // Middleware aliases
        $middleware->alias([
            'auth' => \App\Http\Middleware\Authenticate::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'signed' => \App\Http\Middleware\ValidateSignature::class,
            'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Never flash sensitive fields
        $exceptions->dontFlash([
            'current_password',
            'password',
            'password_confirmation',
        ]);

        // JSON response for unauthenticated API requests
        $exceptions->renderable(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated. Please log in.',
                ], 401);
            }
        });
    })
    ->create();
