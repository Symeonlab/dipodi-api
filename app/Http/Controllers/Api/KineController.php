<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
            ->groupBy('sub_category'); // Groups by "QUADRICEPS", "HANCHE", etc.

        return response()->json($kineData);
    }

    // Gets just the IDs of the user's favorites
    public function getFavorites(Request $request)
    {
        // --- THIS IS THE FIX ---
        // We must specify 'exercises.id' to be unambiguous
        return $request->user()->favoriteExercises()->pluck('exercises.id');
        // --- END OF FIX ---
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
