<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserReminderSetting;

class UserReminderSettingSeeder extends Seeder
{
    public function run(): void
    {
        UserReminderSetting::truncate();

        $users = User::all();

        foreach ($users as $user) {
            UserReminderSetting::create([
                'user_id' => $user->id,
                'breakfast_enabled' => true,
                'breakfast_time' => '08:00:00',
                'lunch_enabled' => true,
                'lunch_time' => '12:00:00',
                'dinner_enabled' => true,
                'dinner_time' => '19:00:00',
                'workout_enabled' => true,
                'workout_time' => '17:00:00',
            ]);
        }
    }
}
