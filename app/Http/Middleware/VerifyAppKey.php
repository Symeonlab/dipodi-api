<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Http\Responses\ApiResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Verify that the request comes from an authorized client (iOS app).
 *
 * Every API request must include an X-App-Key header whose SHA-256 hash
 * matches the hashed key stored in the DIPODI_APP_KEY_HASH env variable.
 *
 * This prevents unauthorised clients (Postman, cURL, web scrapers, etc.)
 * from accessing the API even if they have a valid user token.
 */
class VerifyAppKey
{
    public function handle(Request $request, Closure $next): Response
    {
        $expectedHash = config('app.dipodi_app_key_hash');

        // If no hash configured, skip in local/testing environments
        if (empty($expectedHash) && app()->environment('local', 'testing')) {
            return $next($request);
        }

        if (empty($expectedHash)) {
            return ApiResponse::serverError(__('api.app_misconfigured'));
        }

        $appKey = $request->header('X-App-Key');

        if (empty($appKey)) {
            return ApiResponse::unauthorized(__('api.missing_app_key'));
        }

        // Compare hash of provided key with stored hash
        if (!hash_equals($expectedHash, hash('sha256', $appKey))) {
            return ApiResponse::forbidden(__('api.invalid_app_key'));
        }

        return $next($request);
    }
}
