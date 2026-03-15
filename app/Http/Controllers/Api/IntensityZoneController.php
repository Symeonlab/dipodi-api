<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Models\IntensityZone;
use Illuminate\Http\JsonResponse;

class IntensityZoneController extends Controller
{
    public function index(): JsonResponse
    {
        $zones = IntensityZone::orderBy('sort_order')->get()->map(fn ($z) => [
            'id' => $z->id,
            'color' => $z->color,
            'name' => $z->name,
            'intensity_range' => $z->intensity_range,
            'description' => $z->description,
            'rpe_min' => $z->rpe_min,
            'rpe_max' => $z->rpe_max,
        ]);

        return ApiResponse::success($zones);
    }
}
