<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\NutritionPlanController;
use App\Http\Controllers\Api\OnboardingController;
use App\Http\Controllers\Api\SocialAuthController;
use App\Http\Controllers\Api\UserProfileController;
use App\Http\Controllers\Api\WorkoutPlanController;
use App\Http\Controllers\Api\KineController;
use App\Http\Controllers\Api\SettingsController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// --- 1. PUBLIC AUTH ROUTES (No login needed) ---
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);

    // Social Logins (Google, Facebook, Apple)
    Route::post('/{provider}/login', [SocialAuthController::class, 'handleSocialLogin'])
        ->whereIn('provider', ['google', 'facebook', 'apple']);
});

     // --- 2. ONBOARDING DATA (No login needed) ---
     Route::get('/onboarding-data', [OnboardingController::class, 'getOnboardingData']);

     // --- 3. PROTECTED ROUTES (Requires API Token) ---
     Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // User & Profile
    Route::get('/user', [UserProfileController::class, 'show']); // Gets the logged-in user
    Route::put('/user/profile', [UserProfileController::class, 'update']); // Updates user profile (at end of onboarding)

    // Main App
    Route::get('/dashboard-metrics', [DashboardController::class, 'getMetrics']);

    // Nutrition
    Route::get('/nutrition-plan', [NutritionPlanController::class, 'generate']);

    // Workouts
    Route::post('/workout-plan/generate', [WorkoutPlanController::class, 'generate']);
    Route::get('/workout-plan', [WorkoutPlanController::class, 'getWeeklyPlan']);
    Route::post('/user-progress', [WorkoutPlanController::class, 'logProgress']);

    // --- Kine Tab ---
    Route::get('/kine-data', [KineController::class, 'getKineData']);
    Route::get('/kine-favorites', [KineController::class, 'getFavorites']);
    Route::post('/kine-favorites/toggle', [KineController::class, 'toggleFavorite']);

    // --- Settings ---
    Route::get('/settings/reminders', [SettingsController::class, 'getReminderSettings']);
    Route::put('/settings/reminders', [SettingsController::class, 'updateReminderSettings']);

    Route::get('/user-progress', [WorkoutPlanController::class, 'getProgress']);
    Route::get('/nutrition-plan', [NutritionPlanController::class, 'generate']);
});
