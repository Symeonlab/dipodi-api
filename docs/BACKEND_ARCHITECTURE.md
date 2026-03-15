# Dipoddi API - Backend Architecture & User Guide

## Table of Contents

1. [Overview](#overview)
2. [System Architecture Diagram](#system-architecture-diagram)
3. [What the User Sees (iOS App)](#what-the-user-sees-ios-app)
4. [User Journey Flow](#user-journey-flow)
5. [Authentication Flow](#authentication-flow)
6. [Backend API Structure](#backend-api-structure)
7. [Database Schema](#database-schema)
8. [API Endpoints Reference](#api-endpoints-reference)
9. [Services & Business Logic](#services--business-logic)
10. [Admin Panel](#admin-panel)
11. [Caching & Performance](#caching--performance)
12. [Multilingual Support](#multilingual-support)

---

## Overview

Dipoddi is a **personalized fitness & nutrition platform** for football players and fitness enthusiasts. It consists of:

- **Laravel Backend API** - Serves data, generates workout/nutrition plans, manages users
- **iOS App (SwiftUI)** - The mobile client users interact with
- **Filament Admin Panel** - Web dashboard for admins/coaches to manage content

The backend is built with **Laravel 11 + Sanctum** for token auth, and the iOS app communicates via a REST JSON API.

---

## System Architecture Diagram

```
┌─────────────────────────────────────────────────────────────────────────┐
│                         DIPODI SYSTEM OVERVIEW                         │
└─────────────────────────────────────────────────────────────────────────┘

  ┌──────────────┐         HTTPS / JSON           ┌──────────────────────┐
  │              │  ◄──────────────────────────►   │                      │
  │   iOS App    │    Authorization: Bearer token  │   Laravel Backend    │
  │  (SwiftUI)   │                                 │   (API + Services)   │
  │              │    Accept-Language: en|fr|ar     │                      │
  └──────┬───────┘                                 └──────────┬───────────┘
         │                                                    │
         │                                                    │
  ┌──────▼───────┐                                 ┌──────────▼───────────┐
  │  Keychain    │                                 │   MySQL Database     │
  │  (Token)     │                                 │   (All app data)     │
  └──────────────┘                                 └──────────┬───────────┘
                                                              │
  ┌──────────────┐                                 ┌──────────▼───────────┐
  │  HealthKit   │                                 │   Filament Admin     │
  │  (Steps,     │                                 │   Panel (Web UI)     │
  │   Calories)  │                                 │   /admin             │
  └──────────────┘                                 └──────────────────────┘


┌─────────────────────────────────────────────────────────────────────────┐
│                        REQUEST / RESPONSE FLOW                         │
│                                                                        │
│   iOS App                    Laravel API                  Database     │
│   ───────                    ──────────                  ────────      │
│                                                                        │
│   ┌─────────┐   POST /auth/login   ┌──────────────┐                  │
│   │ Login   │ ──────────────────►   │ AuthController│                  │
│   │ Screen  │   {email, password}   │              │──► users table    │
│   │         │ ◄──────────────────   │  Sanctum     │                  │
│   └─────────┘   {token, user}       └──────────────┘                  │
│                                                                        │
│   ┌─────────┐   GET /workout-plan   ┌──────────────┐                  │
│   │ Workout │ ──────────────────►   │WorkoutPlan   │                  │
│   │  Tab    │   Bearer: <token>     │ Controller   │──► sessions +    │
│   │         │ ◄──────────────────   │  + Generator │    exercises     │
│   └─────────┘   {sessions[]}        └──────────────┘                  │
│                                                                        │
│   ┌─────────┐   GET /nutrition-plan ┌──────────────┐                  │
│   │Nutrition│ ──────────────────►   │NutritionPlan │                  │
│   │  Tab    │   Bearer: <token>     │ Controller   │──► food_items +  │
│   │         │ ◄──────────────────   │  + Generator │    user profile  │
│   └─────────┘   {calories, meals}   └──────────────┘                  │
│                                                                        │
└─────────────────────────────────────────────────────────────────────────┘
```

---

## What the User Sees (iOS App)

The app has **5 main tabs** accessible from a floating bottom navigation bar:

```
┌─────────────────────────────────────────────────────────────┐
│                                                             │
│                    ┌─────────────────┐                      │
│                    │  Current Screen  │                      │
│                    │    Content       │                      │
│                    │                  │                      │
│                    └─────────────────┘                      │
│                                                             │
│  ┌──────────────────────────────────────────────────────┐   │
│  │  🏃 Workout  │ 🥬 Nutrition │ 🧘 Kine │ 📚 Blog │ 👤 │   │
│  └──────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────┘
```

### Tab Breakdown

| Tab | Name | What the User Sees |
|-----|------|--------------------|
| 1 | **Workout** | Weekly 7-day calendar, daily exercises with video links, sets/reps/recovery, warmup & finisher routines |
| 2 | **Nutrition** | Daily calorie target, macro breakdown (protein/carbs/fat), meal plan (breakfast/lunch/dinner), water tracker, dietary advice |
| 3 | **Kine** | Exercise library for mobility & strengthening, search & favorites, video demonstrations |
| 4 | **Blog** | Fitness articles, search & category filter, full article reading |
| 5 | **Profile** | User info, active goal, achievements, body measurements, settings, language toggle, sign out |

### Additional Screens (accessible from Profile)

| Screen | Purpose |
|--------|---------|
| **Goals** | Set fitness goals (weight loss, muscle gain, etc.), track progress percentage |
| **Achievements** | View earned/locked badges, leaderboard rankings, points |
| **Feedback** | Answer sport-specific questionnaires, view history |
| **Health Assessment** | Comprehensive health questionnaire, get insights & recommendations |
| **Measurement Log** | Log weight, waist, chest, hips over time |
| **Reminders** | Set workout/nutrition reminder times |

---

## User Journey Flow

```
┌─────────────────────────────────────────────────────────────────────┐
│                     COMPLETE USER JOURNEY                           │
└─────────────────────────────────────────────────────────────────────┘

     ┌──────────────┐
     │  App Launch   │
     │  (Splash)     │
     └──────┬───────┘
            │
            ▼
     ┌──────────────┐     Token found      ┌─────────────────┐
     │ Check Token  │ ──────────────────►  │  Validate Token  │
     │ in Keychain  │                      │  GET /api/user   │
     └──────┬───────┘                      └────────┬────────┘
            │                                       │
            │ No token                              │ Valid
            ▼                                       ▼
     ┌──────────────┐                      ┌─────────────────┐
     │  Auth Screen  │                      │ Onboarding      │
     │              │                      │ complete?        │
     │  - Login     │                      └───┬─────────┬───┘
     │  - Register  │                          │ No      │ Yes
     │  - Guest     │                          ▼         ▼
     └──────┬───────┘                   ┌──────────┐  ┌──────────┐
            │                           │Onboarding│  │ Main App │
            │ POST /auth/login          │ 15 Steps │  │ (5 Tabs) │
            │ or /auth/register         └────┬─────┘  └──────────┘
            ▼                                │
     ┌──────────────┐                        │ PUT /user/profile
     │ Save Token   │                        ▼
     │ to Keychain  │               ┌─────────────────┐
     └──────┬───────┘               │  Generate Plans  │
            │                       │  (Workout +      │
            ▼                       │   Nutrition)     │
     ┌──────────────┐               └────────┬────────┘
     │  Onboarding  │                        │
     │  (15 steps)  │ ◄─────────────────────┘
     └──────────────┘


┌─────────────────────────────────────────────────────────────────────┐
│                     ONBOARDING (15 STEPS)                           │
│                                                                     │
│  Step 0:  Introduction                                              │
│  Step 1:  Gender + Age                                              │
│  Step 2:  Height + Weight                                           │
│  Step 3:  Discipline (Football / Fitness / Other)                   │
│  Step 4:  Player Position (if Football)                             │
│  Step 5:  Fitness Level (Beginner / Intermediate / Advanced)        │
│  Step 6:  Training Location + Days                                  │
│  Step 7:  Fitness Goal (lose weight / gain muscle / maintain)       │
│  Step 8:  Ideal Weight + Activity Level                             │
│  Step 9:  Diet Preferences (vegetarian, meals per day)              │
│  Step 10: Eating Habits (bad habits, snacking)                      │
│  Step 11: Food Consumption (12 categories rated)                    │
│  Step 12: Health Info (medication, diabetes, hormonal)              │
│  Step 13: Medical + Family History                                  │
│  Step 14: Goal Confirmation + Profile Photo                         │
│                                                                     │
│  All 60+ fields are sent to: PUT /api/user/profile                  │
│  Backend marks user as onboarding_complete = true                   │
└─────────────────────────────────────────────────────────────────────┘
```

---

## Authentication Flow

```
┌───────────────────────────────────────────────────────────────────┐
│                    AUTHENTICATION SYSTEM                          │
│                                                                   │
│  Technology: Laravel Sanctum (Token-based)                        │
│  Storage:    iOS Keychain (client) / personal_access_tokens (DB)  │
│                                                                   │
│  ┌─────────┐                           ┌──────────────┐          │
│  │  iOS    │   POST /auth/register     │              │          │
│  │  App    │ ──────────────────────►   │  Auth        │          │
│  │         │   {name, email, pass}     │  Controller  │          │
│  │         │                           │              │          │
│  │         │   POST /auth/login        │  Creates     │          │
│  │         │ ──────────────────────►   │  Sanctum     │          │
│  │         │   {email, pass}           │  Token       │          │
│  │         │                           │              │          │
│  │         │ ◄──────────────────────   │              │          │
│  │         │   {token, user, profile}  │              │          │
│  └─────────┘                           └──────────────┘          │
│                                                                   │
│  Social Login (Google, Facebook, Apple):                          │
│  POST /auth/{provider}/login  →  Socialite verifies  →  Token    │
│                                                                   │
│  Rate Limits:                                                     │
│  ┌──────────────────────────────────────────────────┐            │
│  │  Auth endpoints:     5 requests / minute / IP     │            │
│  │  Regular API:       60 requests / minute / user   │            │
│  │  Heavy operations:  10 requests / minute / user   │            │
│  │  (plan generation, PDF export)                    │            │
│  └──────────────────────────────────────────────────┘            │
│                                                                   │
│  Every authenticated request includes:                            │
│  Authorization: Bearer <token>                                    │
│  Accept-Language: en|fr|ar                                        │
│  Content-Type: application/json                                   │
└───────────────────────────────────────────────────────────────────┘
```

---

## Backend API Structure

```
dipodi-api/
│
├── app/
│   ├── Http/
│   │   ├── Controllers/Api/           # API Endpoints
│   │   │   ├── AuthController         # Login, Register, Social Auth, Logout
│   │   │   ├── UserProfileController  # User profile, Dashboard, Onboarding
│   │   │   ├── WorkoutPlanController  # Workout plans, Progress logging
│   │   │   ├── NutritionController    # Nutrition plans
│   │   │   ├── KineController         # Exercise library + favorites
│   │   │   ├── GoalController         # Goals CRUD + progress
│   │   │   ├── AchievementController  # Achievements + leaderboard
│   │   │   ├── PostController         # Blog posts
│   │   │   ├── FeedbackController     # Feedback questionnaires
│   │   │   ├── HealthAssessmentController  # Health assessments
│   │   │   └── ExportController       # PDF/HTML export
│   │   │
│   │   ├── Middleware/
│   │   │   └── Authenticate           # Sanctum token validation
│   │   │
│   │   └── Requests/Api/              # Form validation rules
│   │
│   ├── Models/                        # Eloquent Models
│   │   ├── User                       # Core user model (roles, relationships)
│   │   ├── UserProfile                # 60+ profile fields
│   │   ├── UserProgress               # Body measurements over time
│   │   ├── UserGoal                   # Fitness goals + tracking
│   │   ├── Achievement                # Badges & points
│   │   ├── Exercise                   # Kine exercise library
│   │   ├── WorkoutSession             # Daily workout sessions
│   │   ├── WorkoutSessionExercise     # Individual exercises in a session
│   │   ├── WorkoutTheme               # Workout theme config
│   │   ├── PlayerProfile              # Player position config
│   │   ├── FeedbackCategory/Question/Session/Answer
│   │   ├── HealthAssessmentCategory/Question/Session/Answer
│   │   ├── Post                       # Blog posts (multilingual)
│   │   └── FoodItem, NutritionAdvice  # Nutrition data
│   │
│   ├── Services/                      # Business Logic
│   │   ├── Workout/
│   │   │   ├── WorkoutPlanGenerator   # Generates 7-day workout plans
│   │   │   └── MatchAwarePlanGenerator # Accounts for match day
│   │   ├── Nutrition/
│   │   │   └── NutritionPlanGenerator # Calculates calories + meal plans
│   │   └── Export/
│   │       └── WorkoutPlanPdfExporter # PDF generation
│   │
│   └── Filament/Admin/               # Admin Panel
│       ├── Resources/                 # CRUD for all entities
│       ├── Widgets/                   # Dashboard widgets
│       └── Pages/                     # Custom admin pages
│
├── routes/
│   └── api.php                        # All API route definitions
│
├── database/
│   ├── migrations/                    # Database schema
│   └── seeders/                       # Sample data
│
└── config/                            # Laravel configuration
```

---

## Database Schema

```
┌─────────────────────────────────────────────────────────────────────┐
│                       DATABASE RELATIONSHIPS                        │
└─────────────────────────────────────────────────────────────────────┘

                         ┌──────────────┐
                         │    users     │
                         │──────────────│
                         │ id           │
                         │ name         │
                         │ email        │
                         │ password     │
                         │ role         │
                         └──────┬───────┘
                                │
           ┌────────────────────┼────────────────────┬──────────────┐
           │ has one            │ has many            │ has many     │
           ▼                    ▼                     ▼              │
  ┌─────────────────┐  ┌───────────────┐  ┌──────────────────┐     │
  │  user_profiles  │  │ user_progress │  │   user_goals     │     │
  │─────────────────│  │───────────────│  │──────────────────│     │
  │ discipline      │  │ date          │  │ goal_type        │     │
  │ position        │  │ weight        │  │ target_weight    │     │
  │ gender, age     │  │ waist, chest  │  │ current_progress │     │
  │ height, weight  │  │ hips, mood    │  │ status           │     │
  │ training_days[] │  │ workout_done  │  │ start/target_date│     │
  │ nutrition prefs │  │ notes         │  │ achievements[]   │     │
  │ medical history │  └───────────────┘  └──────────────────┘     │
  │ 60+ fields...   │                                               │
  └─────────────────┘                                               │
                                                                    │
     ┌──────────────────────────────────────────────────────────────┘
     │ has many           belongs to many        has many
     ▼                    ▼                       ▼
  ┌───────────────┐  ┌───────────────┐  ┌────────────────────┐
  │workout_sessions│  │ achievements │  │ feedback_sessions  │
  │───────────────│  │───────────────│  │────────────────────│
  │ day (LUNDI..) │  │ key           │  │ category_id        │
  │ theme         │  │ name (en/fr/ar)│ │ session_uuid       │
  │ warmup        │  │ description   │  │ average_score      │
  │ finisher      │  │ icon, points  │  │ status, insights   │
  └───────┬───────┘  │ category      │  └────────┬───────────┘
          │          └───────────────┘           │
          │ has many         ▲                   │ has many
          ▼                  │ pivot:            ▼
  ┌────────────────────┐  user_achievements  ┌────────────────┐
  │workout_session_    │  (earned_at)        │feedback_answers│
  │    exercises       │                     │────────────────│
  │────────────────────│                     │ question_id    │
  │ name               │                     │ answer_value   │
  │ sets, reps         │                     └────────────────┘
  │ recovery           │
  │ video_url          │
  └────────────────────┘


  ┌───────────────────────────────────────────────────────────────┐
  │                    OTHER TABLES                                │
  │                                                               │
  │  exercises ──────────── user_favorite_exercises (pivot)        │
  │  (name, category,       (user_id, exercise_id)                │
  │   video_url, met)                                             │
  │                                                               │
  │  posts ──────────────── (title/content in en/fr/ar, slug)     │
  │                                                               │
  │  food_items ─────────── (name, category, tags, nutrition)     │
  │  nutrition_advice ────── (prophetic advice per condition)      │
  │                                                               │
  │  onboarding_options ──── (dropdown choices for onboarding)    │
  │  interests ──────────── (user interest tags)                  │
  │                                                               │
  │  workout_themes ──┬──── workout_theme_rules                   │
  │                   └──── player_profile_themes (pivot)          │
  │  player_profiles ────── (position configs with theme %)       │
  │  bonus_workout_rules ── (level-based finisher configs)        │
  │                                                               │
  │  health_assessment_categories ──► questions ──► sessions      │
  │                                                ──► answers    │
  │                                                               │
  │  feedback_categories ──► questions ──► sessions ──► answers   │
  │                                                               │
  │  user_reminder_settings (per-user reminder preferences)       │
  │  push_notifications     (notification history)                │
  └───────────────────────────────────────────────────────────────┘
```

---

## API Endpoints Reference

### Standard Response Format

All endpoints return JSON in this format:

```json
{
  "success": true,
  "message": "Description of result",
  "data": { }
}
```

Errors return:

```json
{
  "success": false,
  "message": "Error description",
  "errors": { "field": ["Validation message"] }
}
```

### Endpoint Map

```
┌─────────────────────────────────────────────────────────────────────┐
│                     PUBLIC ENDPOINTS (no token)                      │
├─────────────────────────────────────────────────────────────────────┤
│ POST   /api/auth/register              Register new user            │
│ POST   /api/auth/login                 Login with email/password    │
│ POST   /api/auth/{provider}/login      Social login (google/fb/apple)│
│ POST   /api/auth/forgot-password       Send password reset email    │
│ GET    /api/onboarding-data            Get onboarding dropdown data │
│ GET    /api/posts                       Get published blog posts    │
│ GET    /api/posts/latest               Get latest blog posts        │
│ GET    /api/posts/{slug}               Get single blog post         │
└─────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────┐
│                  PROTECTED ENDPOINTS (Bearer token)                  │
├──────────────────────────────────┬──────────────────────────────────┤
│  USER & PROFILE                  │  WORKOUTS                        │
│  GET    /api/user                │  GET    /api/workout-plan        │
│  PUT    /api/user/profile        │  POST   /api/workout-plan/generate│
│  GET    /api/dashboard-metrics   │  GET    /api/user-progress       │
│                                  │  POST   /api/user-progress       │
├──────────────────────────────────┼──────────────────────────────────┤
│  NUTRITION                       │  KINE (EXERCISES)                │
│  GET    /api/nutrition-plan      │  GET    /api/kine-data           │
│                                  │  GET    /api/kine-favorites      │
│                                  │  POST   /api/kine-favorites/toggle│
├──────────────────────────────────┼──────────────────────────────────┤
│  GOALS                           │  ACHIEVEMENTS                    │
│  GET    /api/goals               │  GET    /api/achievements        │
│  GET    /api/goals/active        │  GET    /api/achievements/earned │
│  POST   /api/goals               │  GET    /api/achievements/{id}  │
│  GET    /api/goals/{id}          │  GET    /api/achievements/       │
│  POST   /api/goals/{id}/progress │         leaderboard             │
│  PUT    /api/goals/{id}/status   │                                  │
├──────────────────────────────────┼──────────────────────────────────┤
│  FEEDBACK                        │  HEALTH ASSESSMENT               │
│  GET    /api/feedback/categories │  GET    /api/health-assessment/  │
│  GET    /api/feedback/           │         categories               │
│         questions/{category}     │  GET    /api/health-assessment/  │
│  POST   /api/feedback/submit     │         questions/{category}     │
│  GET    /api/feedback/history    │  GET    /api/health-assessment/full│
│  GET    /api/feedback/stats      │  POST   /api/health-assessment/start│
│  GET    /api/feedback/           │  POST   /api/health-assessment/  │
│         sessions/{id}            │         submit                   │
│                                  │  GET    /api/health-assessment/  │
│                                  │         history                  │
│                                  │  GET    /api/health-assessment/  │
│                                  │         sessions/{id}            │
│                                  │  GET    /api/health-assessment/  │
│                                  │         insights                 │
├──────────────────────────────────┼──────────────────────────────────┤
│  SETTINGS                        │  EXPORT                          │
│  GET    /api/settings/reminders  │  GET    /api/export/             │
│  PUT    /api/settings/reminders  │         workout-plan/pdf         │
│                                  │  GET    /api/export/             │
│                                  │         workout-plan/html        │
├──────────────────────────────────┼──────────────────────────────────┤
│  POSTS (also public above)       │                                  │
│  POST   /api/auth/logout         │                                  │
└──────────────────────────────────┴──────────────────────────────────┘
```

---

## Services & Business Logic

### How Workout Plans Are Generated

```
┌─────────────────────────────────────────────────────────────────────┐
│                   WORKOUT PLAN GENERATION                            │
│                   POST /api/workout-plan/generate                    │
└─────────────────────────────────────────────────────────────────────┘

  User taps "Generate Plan"
       │
       ▼
  ┌─────────────────────┐
  │ WorkoutPlanGenerator │
  └──────────┬──────────┘
             │
             ├──► 1. Delete old workout sessions for user
             │
             ├──► 2. MatchAwarePlanGenerator
             │       │
             │       ├── Read user's match_day from profile
             │       ├── Plan rest day BEFORE match
             │       ├── Plan recovery day AFTER match
             │       └── Return 7-day schedule template
             │
             ├──► 3. For each day (Mon-Sun):
             │       │
             │       ├── getDynamicTheme()
             │       │   ├── Find user's player_profile
             │       │   ├── Get themes for that profile with % weights
             │       │   ├── Filter by training_location
             │       │   └── Pick weighted random theme
             │       │
             │       ├── getExercisesForTheme()
             │       │   ├── Query exercises by category/sub_category
             │       │   ├── Apply theme rules (sets, reps, recovery)
             │       │   └── Return 4-6 exercises
             │       │
             │       ├── Add warmup routine
             │       │
             │       └── getDynamicBonusFinisher()
             │           ├── Lookup BonusWorkoutRule for user's level
             │           └── Return finisher string
             │
             └──► 4. Save 7 WorkoutSessions + exercises to DB

  Result: User gets personalized 7-day plan based on:
    - Their sport discipline & position
    - Training location (gym/outdoor/home)
    - Fitness level (beginner/intermediate/advanced)
    - Match day schedule (rest before, recovery after)
```

### How Nutrition Plans Are Generated

```
┌─────────────────────────────────────────────────────────────────────┐
│                   NUTRITION PLAN GENERATION                          │
│                   GET /api/nutrition-plan                            │
└─────────────────────────────────────────────────────────────────────┘

  User opens Nutrition tab
       │
       ▼
  ┌──────────────────────┐
  │ NutritionPlanGenerator│
  └──────────┬───────────┘
             │
             ├──► 1. Calculate Daily Calories (Harris-Benedict)
             │       │
             │       ├── BMR = based on gender, weight, height, age
             │       ├── TDEE = BMR x activity_level multiplier
             │       │     sedentary:  x 1.2
             │       │     light:      x 1.375
             │       │     moderate:   x 1.55
             │       │     active:     x 1.725
             │       │     very_active: x 1.9
             │       │
             │       └── Adjust for goal:
             │             weight_loss:  - 500 calories
             │             muscle_gain:  + 300 calories
             │             maintain:     no change
             │
             ├──► 2. Calculate Macros
             │       ├── Protein: 30% of calories
             │       ├── Carbs:   40% of calories
             │       └── Fat:     30% of calories
             │
             ├──► 3. Generate Meals
             │       ├── Breakfast: based on user preferences
             │       ├── Lunch:     query FoodItem table
             │       ├── Dinner:    query FoodItem table
             │       └── Snack:     (if muscle_gain goal)
             │       Note: respects is_vegetarian flag
             │
             └──► 4. Get Dietary Advice
                     ├── Query NutritionAdvice by medical_history
                     ├── Match family_history conditions
                     └── Return prophetic medicine guidance

  Result: Personalized plan with calories, macros, meals, and advice
  Cached for 1 hour (cache key = profile hash + locale)
```

### How the Achievement System Works

```
┌─────────────────────────────────────────────────────────────────────┐
│                     ACHIEVEMENT SYSTEM                               │
└─────────────────────────────────────────────────────────────────────┘

  Achievement Types:
  ┌─────────────────────────────────────────────────┐
  │  first_workout       First workout completed     │
  │  week_streak_4       4 consecutive weeks          │
  │  week_streak_8       8 consecutive weeks          │
  │  week_streak_12      12 consecutive weeks         │
  │  progress_25         25% of goal reached          │
  │  progress_50         50% of goal reached          │
  │  progress_75         75% of goal reached          │
  │  goal_completed      100% goal achieved           │
  └─────────────────────────────────────────────────┘

  Trigger: When user updates goal progress (POST /goals/{id}/progress)
       │
       ▼
  UserGoal.checkAchievements()
       │
       ├── Check progress milestones (25/50/75/100%)
       │   └── Award achievement if not already earned
       │
       ├── Check consecutive week streaks
       │   └── calculateConsecutiveWeeks()
       │       └── Award streak achievement if threshold met
       │
       └── Save earned achievements to pivot table
           with earned_at timestamp

  Leaderboard: GET /api/achievements/leaderboard
       └── Ranks users by total points from earned achievements
```

---

## Admin Panel

The Filament admin panel is accessible at `/admin` for users with role `admin`, `coach`, or `manager`.

```
┌─────────────────────────────────────────────────────────────────────┐
│                     ADMIN PANEL (/admin)                             │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  Dashboard Widgets:                                                 │
│  ┌──────────────┐ ┌──────────────┐ ┌──────────────┐               │
│  │ API Stats    │ │ User Activity│ │ Onboarding   │               │
│  │ (requests,   │ │ (active,     │ │ Stats        │               │
│  │  errors)     │ │  new users)  │ │ (completion  │               │
│  │              │ │              │ │  rate)        │               │
│  └──────────────┘ └──────────────┘ └──────────────┘               │
│  ┌──────────────┐ ┌──────────────┐ ┌──────────────┐               │
│  │ Achievements │ │ Feedback     │ │ Health       │               │
│  │ Overview     │ │ Overview     │ │ Assessment   │               │
│  └──────────────┘ └──────────────┘ └──────────────┘               │
│  ┌──────────────┐ ┌──────────────┐ ┌──────────────┐               │
│  │ Exercise     │ │ Food         │ │ Goal         │               │
│  │ Category     │ │ Category     │ │ Progress     │               │
│  │ Chart        │ │ Chart        │ │ Widget       │               │
│  └──────────────┘ └──────────────┘ └──────────────┘               │
│                                                                     │
│  Manageable Resources:                                              │
│  ├── Users (view profiles, feedback sessions, assessments)         │
│  ├── Achievements (create/edit badges, set points)                 │
│  ├── Workout Themes & Rules                                        │
│  ├── Player Profiles & Theme Mappings                              │
│  ├── Feedback Categories & Questions                               │
│  ├── Health Assessment Categories & Questions                      │
│  ├── User Goals                                                    │
│  ├── Blog Posts                                                    │
│  ├── Push Notifications                                            │
│  ├── Onboarding Options                                            │
│  ├── Interests                                                     │
│  └── Food Items                                                    │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

## Caching & Performance

```
┌──────────────────────────────────────────────────────┐
│              CACHING STRATEGY                         │
├──────────────────────────────────────────────────────┤
│                                                      │
│  Endpoint                    Cache Duration  Refresh │
│  ─────────────────────────   ──────────────  ─────── │
│  GET /workout-plan           30 minutes      ?refresh │
│  GET /user-progress          30 minutes      ?refresh │
│  GET /nutrition-plan         1 hour          auto     │
│                                                      │
│  Cache invalidation:                                 │
│  - Workout plan regeneration clears workout cache    │
│  - Nutrition cache key includes profile hash,        │
│    so profile changes create a new cache entry       │
│  - ?refresh=true query param bypasses cache          │
│                                                      │
│  Performance indexes on:                             │
│  - (discipline, is_active) for filtered queries      │
│  - (user_id, status) for goal lookups                │
│  - (user_id, category_id) for feedback/assessment    │
│  - (user_id, created_at) for time-based queries      │
│                                                      │
└──────────────────────────────────────────────────────┘
```

---

## Multilingual Support

The API supports 3 languages via the `Accept-Language` header:

```
┌──────────────────────────────────────────────────────┐
│              LANGUAGE SUPPORT                         │
├──────────────────────────────────────────────────────┤
│                                                      │
│  Language    Code   Direction   Coverage              │
│  ──────────  ────   ─────────   ────────              │
│  English     en     LTR         Full                  │
│  French      fr     LTR         Full                  │
│  Arabic      ar     RTL         Full                  │
│                                                      │
│  Multilingual fields in database:                    │
│  - name_en, name_fr, name_ar                         │
│  - title_en, title_fr, title_ar                      │
│  - content_en, content_fr, content_ar                │
│  - description_en, description_fr, description_ar    │
│  - question_en, question_fr, question_ar             │
│                                                      │
│  API returns localized content based on:             │
│  Accept-Language header → app()->getLocale()         │
│  Fallback chain: requested → en → fr                 │
│                                                      │
│  iOS app sends language via:                         │
│  LanguageManager.shared.currentLanguage              │
│  Maps to: Accept-Language header on every request    │
│                                                      │
└──────────────────────────────────────────────────────┘
```

---

## Full Data Flow Example: User Logs a Workout

```
┌─────────────────────────────────────────────────────────────────────┐
│  EXAMPLE: User completes a workout and logs measurements            │
└─────────────────────────────────────────────────────────────────────┘

  iOS App                         Laravel API                Database
  ─────────                       ──────────                 ────────

  1. User opens Workout tab
     │
     ├── GET /api/workout-plan ──► WorkoutPlanController
     │   Authorization: Bearer     │
     │   Accept-Language: fr       ├── Check cache (30 min)
     │                             ├── If cached → return
     │                             ├── Else: query WorkoutSessions
     │                             │   with exercises for user
     │ ◄────── 200 OK ────────────┤
     │   {sessions: [...]}         └── Cache result
     │
  2. User views today's exercises
     │   (rendered from cached data, no API call)
     │
  3. User completes workout, logs measurements
     │
     ├── POST /api/user-progress ► WorkoutPlanController
     │   {                          │
     │     date: "2026-02-05",      ├── Validate request
     │     weight: 82.5,            ├── Create/update UserProgress
     │     waist: 78,               ├── Clear progress cache
     │     workout_completed: true  │
     │   }                          │
     │ ◄────── 201 Created ────────┘
     │   {progress: {...}}
     │
  4. User checks goal progress
     │
     ├── POST /goals/1/progress ──► GoalController
     │                              │
     │                              ├── Recalculate progress %
     │                              ├── Check if on track
     │                              ├── Check achievement unlocks
     │                              │   └── If milestone hit:
     │                              │       attach achievement
     │ ◄────── 200 OK ────────────┤
     │   {progress: 67%,           └── Return updated goal
     │    is_on_track: true,
     │    new_achievements: [...]}
     │
  5. App shows achievement popup
     (local UI animation)

└─────────────────────────────────────────────────────────────────────┘
```

---

## Quick Reference: How Each Feature Connects

```
┌──────────────┬────────────────────────┬────────────────────────────┐
│ User Feature │ iOS Screen             │ Backend Component          │
├──────────────┼────────────────────────┼────────────────────────────┤
│ Sign up      │ RegisterView           │ AuthController@register    │
│ Log in       │ LoginView              │ AuthController@login       │
│ Social login │ LoginView              │ AuthController@socialLogin │
│ Onboarding   │ OnboardingFlow (15)    │ UserProfileController      │
│ Workouts     │ WorkoutView            │ WorkoutPlanController      │
│              │                        │ + WorkoutPlanGenerator     │
│ Nutrition    │ NutritionView          │ NutritionController        │
│              │                        │ + NutritionPlanGenerator   │
│ Exercises    │ KineView               │ KineController             │
│ Goals        │ GoalsView              │ GoalController             │
│ Achievements │ AchievementsFullView   │ AchievementController      │
│ Blog         │ BlogPostListView       │ PostController             │
│ Feedback     │ FeedbackView           │ FeedbackController         │
│ Health check │ HealthAssessmentView   │ HealthAssessmentController │
│ Measurements │ MeasurementLogView     │ WorkoutPlanController      │
│ Export PDF   │ ProfileView            │ ExportController           │
│ Settings     │ ProfileView            │ SettingsController         │
│ Admin panel  │ (web browser)          │ Filament /admin            │
└──────────────┴────────────────────────┴────────────────────────────┘
```
