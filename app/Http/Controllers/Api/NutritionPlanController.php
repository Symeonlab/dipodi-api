<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Nutrition\NutritionPlanGenerator;

class NutritionPlanController extends Controller
{
    /**
     * Generate and return the user's personalized nutrition plan.
     */
    public function generate(Request $request)
    {
        $user = $request->user();

        // Use the service we created to generate the plan dynamically
        $generator = new NutritionPlanGenerator($user);
        $plan = $generator->generatePlan();

        return response()->json($plan);
    }
}
