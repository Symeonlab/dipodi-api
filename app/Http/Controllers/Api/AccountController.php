<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    /**
     * Export all user data (GDPR Article 15 - Right of Access / Article 20 - Data Portability).
     * Returns all user data in a structured JSON format.
     */
    public function exportData(Request $request): JsonResponse
    {
        $user = $request->user();

        $data = [
            'account' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => $user->created_at,
            ],
            'profile' => $user->profile,
            'goals' => $user->goals()->get(),
            'progress' => $user->progressLogs()->get(),
            'workout_sessions' => $user->workoutSessions()->get(),
            'feedback_sessions' => $user->feedbackSessions()->with('answers')->get(),
            'workout_feedback' => \App\Models\WorkoutFeedback::where('user_id', $user->id)->get(),
            'health_assessments' => $user->healthAssessmentSessions()->with('answers')->get(),
            'achievements' => $user->achievements()->get(),
            'reminder_settings' => $user->reminderSettings,
            'exported_at' => now()->toIso8601String(),
        ];

        return ApiResponse::success($data, __('api.data_exported'));
    }

    /**
     * Delete user account (GDPR Article 17 - Right to Erasure).
     * Requires password confirmation.
     */
    public function deleteAccount(Request $request): JsonResponse
    {
        $request->validate([
            'password' => 'required|string',
            'confirmation' => 'required|string|in:DELETE',
        ]);

        $user = $request->user();

        if (!Hash::check($request->password, $user->password)) {
            return ApiResponse::error(__('auth.current_password_incorrect'), 422);
        }

        // Revoke all tokens first
        $user->tokens()->delete();

        // Delete the user (cascading foreign keys handle related data)
        $user->delete();

        return ApiResponse::success(null, __('api.account_deleted'));
    }

    /**
     * Get privacy policy and terms of service URLs.
     */
    public function privacyInfo(): JsonResponse
    {
        return ApiResponse::success([
            'privacy_policy_url' => config('app.privacy_policy_url', 'https://api.dipoddi.com/privacy'),
            'terms_of_service_url' => config('app.terms_of_service_url', 'https://api.dipoddi.com/terms'),
            'data_retention' => [
                'account_data' => __('api.retention_until_deletion'),
                'health_data' => __('api.retention_until_deletion'),
                'workout_data' => __('api.retention_until_deletion'),
                'feedback_data' => __('api.retention_until_deletion'),
            ],
            'contact_email' => config('app.support_email', 'privacy@dipoddi.com'),
        ]);
    }
}
