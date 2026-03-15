<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserGoal;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class GoalController extends Controller
{
    /**
     * Get all goals for the authenticated user.
     */
    public function index(Request $request): JsonResponse
    {
        $goals = UserGoal::where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($goal) {
                return [
                    'id' => $goal->id,
                    'goal_type' => $goal->goal_type,
                    'goal_type_label' => $goal->goal_type_label,
                    'status' => $goal->status,
                    'progress' => round($goal->current_progress_percentage, 1),
                    'is_on_track' => $goal->isOnTrack(),
                    'target_weight' => $goal->target_weight,
                    'target_waist' => $goal->target_waist,
                    'target_workouts_per_week' => $goal->target_workouts_per_week,
                    'start_date' => $goal->start_date?->format('Y-m-d'),
                    'target_date' => $goal->target_date?->format('Y-m-d'),
                    'weeks_completed' => $goal->weeks_completed,
                    'total_weeks' => $goal->total_weeks,
                    'achievements' => $goal->achievements ?? [],
                    'created_at' => $goal->created_at->toIso8601String(),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $goals,
        ]);
    }

    /**
     * Get the user's active goal.
     */
    public function active(Request $request): JsonResponse
    {
        $goal = UserGoal::where('user_id', $request->user()->id)
            ->where('status', 'active')
            ->first();

        if (!$goal) {
            return response()->json([
                'success' => true,
                'data' => null,
                'message' => __('No active goal found'),
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $goal->id,
                'goal_type' => $goal->goal_type,
                'goal_type_label' => $goal->goal_type_label,
                'status' => $goal->status,
                'progress' => round($goal->current_progress_percentage, 1),
                'expected_progress' => round($goal->getExpectedProgress(), 1),
                'is_on_track' => $goal->isOnTrack(),
                'target_weight' => $goal->target_weight,
                'target_waist' => $goal->target_waist,
                'target_chest' => $goal->target_chest,
                'target_hips' => $goal->target_hips,
                'start_weight' => $goal->start_weight,
                'start_waist' => $goal->start_waist,
                'target_workouts_per_week' => $goal->target_workouts_per_week,
                'start_date' => $goal->start_date?->format('Y-m-d'),
                'target_date' => $goal->target_date?->format('Y-m-d'),
                'weeks_completed' => $goal->weeks_completed,
                'total_weeks' => $goal->total_weeks,
                'achievements' => $goal->achievements ?? [],
                'notes' => $goal->notes,
            ],
        ]);
    }

    /**
     * Create a new goal.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'goal_type' => 'required|in:weight_loss,muscle_gain,maintain,custom',
            'target_weight' => 'nullable|numeric|min:30|max:300',
            'target_waist' => 'nullable|numeric|min:30|max:200',
            'target_chest' => 'nullable|numeric|min:30|max:200',
            'target_hips' => 'nullable|numeric|min:30|max:200',
            'target_workouts_per_week' => 'nullable|integer|min:1|max:7',
            'total_weeks' => 'nullable|integer|min:1|max:52',
            'target_date' => 'nullable|date|after:today',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => __('Validation error'),
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = $request->user();

        // Check if user has an active goal
        $activeGoal = UserGoal::where('user_id', $user->id)
            ->where('status', 'active')
            ->first();

        if ($activeGoal) {
            return response()->json([
                'success' => false,
                'message' => __('You already have an active goal. Please complete or abandon it first.'),
            ], 400);
        }

        // Get current measurements from latest progress log
        $latestProgress = $user->progressLogs()->latest('date')->first();
        $userProfile = $user->profile;

        $totalWeeks = $request->input('total_weeks', 12);
        $targetDate = $request->input('target_date')
            ? \Carbon\Carbon::parse($request->input('target_date'))
            : now()->addWeeks($totalWeeks);

        $goal = UserGoal::create([
            'user_id' => $user->id,
            'goal_type' => $request->input('goal_type'),
            'target_weight' => $request->input('target_weight'),
            'target_waist' => $request->input('target_waist'),
            'target_chest' => $request->input('target_chest'),
            'target_hips' => $request->input('target_hips'),
            'target_workouts_per_week' => $request->input('target_workouts_per_week', 3),
            'start_weight' => $latestProgress?->weight ?? $userProfile?->weight,
            'start_waist' => $latestProgress?->waist ?? $userProfile?->waist,
            'start_chest' => $latestProgress?->chest ?? $userProfile?->chest,
            'start_hips' => $latestProgress?->hips ?? $userProfile?->hips,
            'start_date' => now(),
            'target_date' => $targetDate,
            'total_weeks' => $totalWeeks,
            'status' => 'active',
            'current_progress_percentage' => 0,
            'weeks_completed' => 0,
            'notes' => $request->input('notes'),
        ]);

        return response()->json([
            'success' => true,
            'message' => __('Goal created successfully'),
            'data' => [
                'id' => $goal->id,
                'goal_type' => $goal->goal_type,
                'status' => $goal->status,
            ],
        ], 201);
    }

    /**
     * Update goal progress.
     */
    public function updateProgress(Request $request, UserGoal $goal): JsonResponse
    {
        // Ensure user owns this goal
        if ($goal->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => __('Unauthorized'),
            ], 403);
        }

        if ($goal->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => __('Goal is not active'),
            ], 400);
        }

        // Calculate new progress
        $newProgress = $goal->calculateProgress();

        // Update weeks completed based on time elapsed
        $weeksElapsed = $goal->start_date->diffInWeeks(now());

        $goal->update([
            'current_progress_percentage' => $newProgress,
            'weeks_completed' => min($weeksElapsed, $goal->total_weeks),
        ]);

        // Check for new achievements
        $newAchievements = $goal->checkAchievements();

        // Check if goal is complete
        if ($newProgress >= 100 && $goal->status === 'active') {
            $goal->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => __('Progress updated'),
            'data' => [
                'progress' => round($newProgress, 1),
                'weeks_completed' => $goal->weeks_completed,
                'status' => $goal->status,
                'new_achievements' => $newAchievements,
            ],
        ]);
    }

    /**
     * Update goal status (pause, resume, abandon).
     */
    public function updateStatus(Request $request, UserGoal $goal): JsonResponse
    {
        // Ensure user owns this goal
        if ($goal->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => __('Unauthorized'),
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:active,paused,abandoned',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => __('Validation error'),
                'errors' => $validator->errors(),
            ], 422);
        }

        $newStatus = $request->input('status');

        // Can't change status of completed goal
        if ($goal->status === 'completed') {
            return response()->json([
                'success' => false,
                'message' => __('Cannot change status of completed goal'),
            ], 400);
        }

        $goal->update(['status' => $newStatus]);

        return response()->json([
            'success' => true,
            'message' => __('Goal status updated'),
            'data' => [
                'status' => $goal->status,
            ],
        ]);
    }

    /**
     * Get goal details.
     */
    public function show(Request $request, UserGoal $goal): JsonResponse
    {
        // Ensure user owns this goal
        if ($goal->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => __('Unauthorized'),
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $goal->id,
                'goal_type' => $goal->goal_type,
                'goal_type_label' => $goal->goal_type_label,
                'status' => $goal->status,
                'progress' => round($goal->current_progress_percentage, 1),
                'expected_progress' => round($goal->getExpectedProgress(), 1),
                'is_on_track' => $goal->isOnTrack(),
                'target_weight' => $goal->target_weight,
                'target_waist' => $goal->target_waist,
                'target_chest' => $goal->target_chest,
                'target_hips' => $goal->target_hips,
                'start_weight' => $goal->start_weight,
                'start_waist' => $goal->start_waist,
                'start_chest' => $goal->start_chest,
                'start_hips' => $goal->start_hips,
                'target_workouts_per_week' => $goal->target_workouts_per_week,
                'start_date' => $goal->start_date?->format('Y-m-d'),
                'target_date' => $goal->target_date?->format('Y-m-d'),
                'completed_at' => $goal->completed_at?->toIso8601String(),
                'weeks_completed' => $goal->weeks_completed,
                'total_weeks' => $goal->total_weeks,
                'achievements' => $goal->achievements ?? [],
                'notes' => $goal->notes,
                'created_at' => $goal->created_at->toIso8601String(),
            ],
        ]);
    }
}
