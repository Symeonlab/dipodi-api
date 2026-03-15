<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\NutritionPlanController;
use App\Http\Controllers\Api\OnboardingController;
use App\Http\Controllers\Api\SocialAuthController;
use App\Http\Controllers\Api\UserProfileController;
use App\Http\Controllers\Api\WorkoutPlanController;
use App\Http\Controllers\Api\KineController;
use App\Http\Controllers\Api\SettingsController;
use App\Http\Controllers\Api\ExportController;
use App\Http\Controllers\Api\GoalController;
use App\Http\Controllers\Api\AchievementController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\FeedbackController;
use App\Http\Controllers\Api\HealthAssessmentController;
use App\Http\Controllers\Api\WorkoutFeedbackController;
use App\Http\Controllers\Api\SleepController;
use App\Http\Controllers\Api\PropheticMedicineController;
use App\Http\Controllers\Api\IntensityZoneController;
use App\Http\Controllers\Api\AccountController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Rate Limiters
|--------------------------------------------------------------------------
*/
RateLimiter::for('auth', function (Request $request) {
    return Limit::perMinute(5)->by($request->ip())->response(function () {
        return response()->json([
            'success' => false,
            'message' => __('auth.too_many_attempts'),
        ], 429);
    });
});

RateLimiter::for('api', function (Request $request) {
    return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
});

RateLimiter::for('heavy', function (Request $request) {
    return Limit::perMinute(10)->by($request->user()?->id ?: $request->ip());
});

// --- 1. PUBLIC AUTH ROUTES (No login needed) ---
// Rate limited to 5 requests per minute to prevent brute force attacks
Route::prefix('auth')->middleware('throttle:auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);

    // Social Logins (Google, Facebook, Apple)
    Route::post('/{provider}/login', [SocialAuthController::class, 'handleSocialLogin'])
        ->whereIn('provider', ['google', 'facebook', 'apple']);
});

// --- 2. PUBLIC ROUTES (No login needed, still rate-limited) ---
Route::middleware('throttle:api')->group(function () {
    Route::get('/onboarding-data', [OnboardingController::class, 'getOnboardingData']);

    // GDPR / Privacy (public)
    Route::get('/privacy', [AccountController::class, 'privacyInfo']);

    // Posts (Public - for viewing published content)
    Route::prefix('posts')->group(function () {
        Route::get('/', [PostController::class, 'index']);
        Route::get('/latest', [PostController::class, 'latest']);
        Route::get('/{slug}', [PostController::class, 'show']);
    });
});

// --- 3. PROTECTED ROUTES (Requires API Token) ---
// Standard rate limit: 60 requests per minute
Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {

    // Auth
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::post('/auth/logout-all', [AuthController::class, 'logoutAll']);
    Route::put('/auth/password', [AuthController::class, 'changePassword']);
    Route::get('/auth/me', [AuthController::class, 'me']);

    // User & Profile
    Route::get('/user', [UserProfileController::class, 'show']);
    Route::put('/user/profile', [UserProfileController::class, 'update']);

    // Main App Dashboard
    Route::get('/dashboard-metrics', [DashboardController::class, 'getMetrics']);

    // --- Kine Tab ---
    Route::get('/kine-data', [KineController::class, 'getKineData']);
    Route::get('/kine-favorites', [KineController::class, 'getFavorites']);
    Route::post('/kine-favorites/toggle', [KineController::class, 'toggleFavorite']);

    // --- Settings ---
    Route::get('/settings/reminders', [SettingsController::class, 'getReminderSettings']);
    Route::put('/settings/reminders', [SettingsController::class, 'updateReminderSettings']);

    // --- Workout Plan (cached read) ---
    Route::get('/workout-plan', [WorkoutPlanController::class, 'getWeeklyPlan']);

    // --- Progress ---
    Route::get('/user-progress', [WorkoutPlanController::class, 'getProgress']);
    Route::post('/user-progress', [WorkoutPlanController::class, 'logProgress']);

    // --- Goals ---
    Route::prefix('goals')->group(function () {
        Route::get('/', [GoalController::class, 'index']);
        Route::get('/active', [GoalController::class, 'active']);
        Route::post('/', [GoalController::class, 'store']);
        Route::get('/{goal}', [GoalController::class, 'show']);
        Route::post('/{goal}/progress', [GoalController::class, 'updateProgress']);
        Route::put('/{goal}/status', [GoalController::class, 'updateStatus']);
    });

    // --- Achievements ---
    Route::prefix('achievements')->group(function () {
        Route::get('/', [AchievementController::class, 'index']);
        Route::get('/earned', [AchievementController::class, 'earned']);
        Route::get('/leaderboard', [AchievementController::class, 'leaderboard']);
        Route::get('/{achievement}', [AchievementController::class, 'show']);
    });

    // --- Feedback ---
    Route::prefix('feedback')->group(function () {
        Route::get('/categories', [FeedbackController::class, 'categories']);
        Route::get('/questions/{categoryKey}', [FeedbackController::class, 'questions']);
        Route::post('/submit', [FeedbackController::class, 'submit']);
        Route::get('/history', [FeedbackController::class, 'history']);
        Route::get('/stats', [FeedbackController::class, 'stats']);
        Route::get('/sessions/{sessionId}', [FeedbackController::class, 'session']);
    });

    // --- Workout Feedback ---
    Route::prefix('workout-feedback')->group(function () {
        Route::get('/questions', [WorkoutFeedbackController::class, 'questions']);
        Route::post('/', [WorkoutFeedbackController::class, 'submit']);
        Route::get('/history', [WorkoutFeedbackController::class, 'history']);
        Route::get('/recommendation/{theme}', [WorkoutFeedbackController::class, 'recommendation']);
    });

    // --- Sleep & Recovery ---
    Route::get('/sleep/protocols', [SleepController::class, 'getProtocols']);
    Route::get('/sleep/chronotypes', [SleepController::class, 'getChronotypes']);
    Route::get('/sleep/calculate', [SleepController::class, 'calculateBedtime']);

    // --- Prophetic Medicine ---
    Route::get('/prophetic-medicine', [PropheticMedicineController::class, 'index']);
    Route::get('/prophetic-medicine/{condition}', [PropheticMedicineController::class, 'show'])
        ->where('condition', '[a-z_]+');

    // --- Intensity Zones ---
    Route::get('/intensity-zones', [IntensityZoneController::class, 'index']);

    // --- GDPR / Account Management ---
    Route::get('/account/export', [AccountController::class, 'exportData']);
    Route::delete('/account', [AccountController::class, 'deleteAccount']);

    // --- Health Assessment ---
    Route::prefix('health-assessment')->group(function () {
        Route::get('/categories', [HealthAssessmentController::class, 'categories']);
        Route::get('/questions/{categoryKey}', [HealthAssessmentController::class, 'questions']);
        Route::get('/full', [HealthAssessmentController::class, 'fullAssessment']);
        Route::post('/start', [HealthAssessmentController::class, 'startSession']);
        Route::post('/submit', [HealthAssessmentController::class, 'submit']);
        Route::get('/history', [HealthAssessmentController::class, 'history']);
        Route::get('/insights', [HealthAssessmentController::class, 'insights']);
        Route::get('/sessions/{sessionId}', [HealthAssessmentController::class, 'session']);
    });
});

// --- 4. HEAVY/EXPENSIVE ROUTES (Lower rate limit: 10 per minute) ---
// These routes involve complex calculations or database operations
Route::middleware(['auth:sanctum', 'throttle:heavy'])->group(function () {
    // Nutrition Plan Generation (expensive calculation)
    Route::get('/nutrition-plan', [NutritionPlanController::class, 'generate']);

    // Workout Plan Generation (expensive calculation)
    Route::post('/workout-plan/generate', [WorkoutPlanController::class, 'generate']);

    // Export Routes (PDF generation is resource intensive)
    Route::get('/export/workout-plan/pdf', [ExportController::class, 'exportWorkoutPlanPdf']);
    Route::get('/export/workout-plan/html', [ExportController::class, 'exportWorkoutPlanHtml']);
});
