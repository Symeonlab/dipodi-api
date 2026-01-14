<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\Workout\WorkoutPlanGenerator;
use App\Models\WorkoutSession;

class WorkoutPlanController extends Controller
{
    /**
     * Generates a new weekly plan for the user.
     */
    public function generate(Request $request)
    {
        $user = Auth::user()->load('profile');

        if (empty($user->profile->position) || empty($user->profile->training_location)) {
            return response()->json(['error' => 'Please complete your player profile (position, training location) to generate a plan.'], 400);
        }

        $generator = new WorkoutPlanGenerator($user);
        $generator->generateAndSaveWeeklyPlan();

        return response()->json(['message' => 'Weekly plan generated successfully.']);
    }

    /**
     * Gets all workout sessions for the user.
     */
    public function getWeeklyPlan(Request $request)
    {
        $sessions = WorkoutSession::where('user_id', $request->user()->id)
            ->with('exercises')
            ->get();

        return response()->json($sessions);
    }

    /**
     * Logs a user's completed workout (from Workout.swift).
     */
    public function logProgress(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'date' => 'required|date',
            'weight' => 'nullable|numeric|min:30|max:300',
            'waist' => 'nullable|numeric|min:30|max:200',
            'chest' => 'nullable|numeric|min:30|max:200',
            'hips' => 'nullable|numeric|min:30|max:200',
            'mood' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:1000',
            'workout_completed' => 'nullable|string|max:255',
        ]);

        $progress = $user->progressLogs()->updateOrCreate(
            ['date' => $validated['date']], // Find by date
            $validated // Update with all new data
        );

        return response()->json($progress, 201);
    }

    /**
     * Gets all progress logs for the authenticated user.
     */
    public function getProgress(Request $request)
    {
        $logs = $request->user()->progressLogs()
            ->orderBy('date', 'desc')
            ->get();

        return response()->json($logs);
    }
}
// Add this relationship to your User.php model:
// public function progressLogs() { return $this->hasMany(UserProgress::class); }
