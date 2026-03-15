<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Models\PropheticRemedy;
use Illuminate\Http\JsonResponse;

class PropheticMedicineController extends Controller
{
    public function index(): JsonResponse
    {
        $conditions = PropheticRemedy::select('condition_key')
            ->distinct()
            ->orderBy('condition_key')
            ->get()
            ->map(function ($item) {
                $remedy = PropheticRemedy::where('condition_key', $item->condition_key)->first();
                return [
                    'condition_key' => $item->condition_key,
                    'condition_name' => $remedy->condition_name,
                    'remedy_count' => PropheticRemedy::where('condition_key', $item->condition_key)->count(),
                ];
            });

        return ApiResponse::success($conditions);
    }

    public function show(string $conditionKey): JsonResponse
    {
        $remedies = PropheticRemedy::forCondition($conditionKey)
            ->orderBy('sort_order')
            ->get()
            ->map(fn ($r) => [
                'id' => $r->id,
                'condition_key' => $r->condition_key,
                'condition_name' => $r->condition_name,
                'element_name' => $r->element_name,
                'mechanism' => $r->mechanism,
                'recipe' => $r->recipe,
                'notes' => $r->notes,
            ]);

        if ($remedies->isEmpty()) {
            return ApiResponse::notFound(__('api.no_remedies_found'));
        }

        return ApiResponse::success($remedies);
    }
}
