<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    // Gets the user's current settings, or creates them if they don't exist
    public function getReminderSettings(Request $request)
    {
        $settings = $request->user()->reminderSettings()->firstOrCreate();
        return response()->json($settings);
    }

    // Updates the user's settings
    public function updateReminderSettings(Request $request)
    {
        $validated = $request->validate([
            'breakfast_enabled' => 'required|boolean',
            'breakfast_time' => 'required|date_format:H:i',
            'lunch_enabled' => 'required|boolean',
            'lunch_time' => 'required|date_format:H:i',
            'dinner_enabled' => 'required|boolean',
            'dinner_time' => 'required|date_format:H:i',
            'workout_enabled' => 'required|boolean',
            'workout_time' => 'required|date_format:H:i',
        ]);

        $settings = $request->user()->reminderSettings()->update($validated);
        return response()->json(['message' => 'Settings updated', 'settings' => $settings]);
    }
}
