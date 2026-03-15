<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UpdateReminderSettingsRequest;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Gets the user's current settings, or creates them if they don't exist.
     */
    public function getReminderSettings(Request $request)
    {
        $settings = $request->user()->reminderSettings()->firstOrCreate();
        return ApiResponse::success($settings);
    }

    /**
     * Updates the user's reminder settings.
     */
    public function updateReminderSettings(UpdateReminderSettingsRequest $request)
    {
        $validated = $request->validated();
        $settings = $request->user()->reminderSettings()->update($validated);

        return ApiResponse::success($settings, __('api.settings_updated'));
    }
}
