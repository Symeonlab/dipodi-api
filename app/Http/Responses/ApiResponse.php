<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

class ApiResponse
{
    /**
     * Return a success response.
     */
    public static function success(mixed $data = null, ?string $message = null, int $statusCode = 200): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message ?? __('common.success'),
        ];

        if ($data !== null) {
            // Handle paginated data
            if ($data instanceof LengthAwarePaginator) {
                $response['data'] = $data->items();
                $response['meta'] = [
                    'current_page' => $data->currentPage(),
                    'last_page' => $data->lastPage(),
                    'per_page' => $data->perPage(),
                    'total' => $data->total(),
                    'from' => $data->firstItem(),
                    'to' => $data->lastItem(),
                ];
                $response['links'] = [
                    'first' => $data->url(1),
                    'last' => $data->url($data->lastPage()),
                    'prev' => $data->previousPageUrl(),
                    'next' => $data->nextPageUrl(),
                ];
            } else {
                $response['data'] = $data;
            }
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Return a created response (201).
     */
    public static function created(mixed $data = null, ?string $message = null): JsonResponse
    {
        return self::success($data, $message ?? __('api.created'), 201);
    }

    /**
     * Return an error response.
     */
    public static function error(?string $message = null, int $statusCode = 400, array $errors = []): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message ?? __('common.error'),
        ];

        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Return a validation error response (422).
     */
    public static function validationError(array $errors, ?string $message = null): JsonResponse
    {
        return self::error($message ?? __('validation.failed'), 422, $errors);
    }

    /**
     * Return a not found response (404).
     */
    public static function notFound(?string $message = null): JsonResponse
    {
        return self::error($message ?? __('api.resource_not_found'), 404);
    }

    /**
     * Return an unauthorized response (401).
     */
    public static function unauthorized(?string $message = null): JsonResponse
    {
        return self::error($message ?? __('api.unauthorized'), 401);
    }

    /**
     * Return a forbidden response (403).
     */
    public static function forbidden(?string $message = null): JsonResponse
    {
        return self::error($message ?? __('api.forbidden'), 403);
    }

    /**
     * Return a server error response (500).
     */
    public static function serverError(?string $message = null): JsonResponse
    {
        return self::error($message ?? __('api.server_error'), 500);
    }

    /**
     * Return a no content response (204).
     */
    public static function noContent(): JsonResponse
    {
        return response()->json(null, 204);
    }

    /**
     * Return a rate limited response (429).
     */
    public static function tooManyRequests(?string $message = null): JsonResponse
    {
        return self::error($message ?? __('api.rate_limited'), 429);
    }
}
