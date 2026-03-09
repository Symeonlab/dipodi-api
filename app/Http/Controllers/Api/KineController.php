<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\Request;
use App\Models\Exercise;

class KineController extends Controller
{
    // Gets all Kine exercises, grouped by category
    public function getKineData()
    {
        $kineData = Exercise::where('category', 'KINE MOBILITÉ')
            ->orWhere('category', 'KINE RENFORCEMENT')
            ->get()
            ->groupBy('sub_category');

        return ApiResponse::success($kineData);
    }

    // Gets just the IDs of the user's favorites
    public function getFavorites(Request $request)
    {
        $ids = $request->user()->favoriteExercises()->pluck('exercises.id');
        return ApiResponse::success($ids);
    }

    // Adds or removes an exercise from the user's favorites
    public function toggleFavorite(Request $request)
    {
        $request->validate(['exercise_id' => 'required|exists:exercises,id']);
        $user = $request->user();

        // 'toggle' adds if not present, removes if present.
        $result = $user->favoriteExercises()->toggle($request->exercise_id);

        return response()->json([
            'status' => 'success',
            // 'attached' will contain an array of IDs that were added
            'attached' => !empty($result['attached']),
        ]);
    }
}
