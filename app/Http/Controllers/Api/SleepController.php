<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Models\SleepProtocol;
use App\Models\Chronotype;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SleepController extends Controller
{
    public function getProtocols(Request $request): JsonResponse
    {
        $request->validate([
            'category' => 'nullable|in:injury,medical,recovery',
        ]);

        $query = SleepProtocol::orderBy('sort_order');

        if ($request->has('category')) {
            $query->where('category', $request->input('category'));
        }

        $protocols = $query->get()->map(fn ($p) => [
            'id' => $p->id,
            'condition_key' => $p->condition_key,
            'condition_name' => $p->condition_name,
            'cycles_min' => $p->cycles_min,
            'cycles_max' => $p->cycles_max,
            'total_sleep' => $p->total_sleep,
            'objective' => $p->objective,
            'category' => $p->category,
        ]);

        return ApiResponse::success($protocols);
    }

    public function getChronotypes(): JsonResponse
    {
        $chronotypes = Chronotype::orderBy('sort_order')->get()->map(fn ($c) => [
            'id' => $c->id,
            'key' => $c->key,
            'name' => $c->name,
            'wake_time' => $c->wake_time,
            'peak_start' => $c->peak_start,
            'peak_end' => $c->peak_end,
            'bedtime' => $c->bedtime,
            'description' => $c->description,
            'character' => $c->character,
            'icon' => $c->icon,
        ]);

        return ApiResponse::success($chronotypes);
    }

    public function calculateBedtime(Request $request): JsonResponse
    {
        $request->validate([
            'wake_time' => 'required|date_format:H:i',
            'cycles' => 'nullable|integer|min:1|max:8',
        ]);

        $wakeTime = $request->input('wake_time');
        $cycles = $request->input('cycles', 5);
        $cycleMinutes = 90;
        $fallAsleepMinutes = 15;

        $wakeTimestamp = strtotime($wakeTime);
        $sleepMinutes = ($cycles * $cycleMinutes) + $fallAsleepMinutes;
        $bedtime = date('H:i', $wakeTimestamp - ($sleepMinutes * 60));

        $options = [];
        for ($c = 4; $c <= 6; $c++) {
            $mins = ($c * $cycleMinutes) + $fallAsleepMinutes;
            $options[] = [
                'cycles' => $c,
                'total_sleep' => gmdate('G\hi', $c * $cycleMinutes * 60),
                'bedtime' => date('H:i', $wakeTimestamp - ($mins * 60)),
            ];
        }

        return ApiResponse::success([
            'wake_time' => $wakeTime,
            'recommended_bedtime' => $bedtime,
            'cycles' => $cycles,
            'total_sleep' => gmdate('G\hi', $cycles * $cycleMinutes * 60),
            'options' => $options,
        ]);
    }
}
