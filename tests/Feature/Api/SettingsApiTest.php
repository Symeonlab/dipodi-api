<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\UserProfile;
use App\Models\ReminderSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingsApiTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private string $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        UserProfile::create(['user_id' => $this->user->id]);
        $this->token = $this->user->createToken('test-token')->plainTextToken;
    }

    /**
     * Test user can get reminder settings.
     */
    public function test_user_can_get_reminder_settings(): void
    {
        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->getJson('/api/settings/reminders');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);
    }

    /**
     * Test unauthenticated user cannot get settings.
     */
    public function test_unauthenticated_user_cannot_get_settings(): void
    {
        $response = $this->getJson('/api/settings/reminders');

        $response->assertStatus(401);
    }

    /**
     * Test user can update reminder settings with valid data.
     */
    public function test_user_can_update_reminder_settings(): void
    {
        // First create settings
        $this->withHeader('Authorization', "Bearer {$this->token}")
            ->getJson('/api/settings/reminders');

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->putJson('/api/settings/reminders', [
                'workout_reminder_enabled' => true,
                'workout_reminder_time' => '08:00',
                'meal_reminder_enabled' => true,
                'meal_reminder_times' => ['07:00', '12:00', '19:00'],
                'water_reminder_enabled' => true,
                'water_reminder_interval' => 60,
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Settings updated successfully',
            ]);
    }

    /**
     * Test settings validates time format.
     */
    public function test_settings_validates_time_format(): void
    {
        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->putJson('/api/settings/reminders', [
                'workout_reminder_time' => 'invalid-time',
            ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'errors' => ['workout_reminder_time'],
            ]);
    }

    /**
     * Test settings validates water interval range.
     */
    public function test_settings_validates_water_interval_min(): void
    {
        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->putJson('/api/settings/reminders', [
                'water_reminder_interval' => 10, // Below min of 30
            ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'errors' => ['water_reminder_interval'],
            ]);
    }

    /**
     * Test settings validates water interval max.
     */
    public function test_settings_validates_water_interval_max(): void
    {
        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->putJson('/api/settings/reminders', [
                'water_reminder_interval' => 600, // Above max of 480
            ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'errors' => ['water_reminder_interval'],
            ]);
    }

    /**
     * Test settings validates progress reminder day.
     */
    public function test_settings_validates_progress_reminder_day(): void
    {
        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->putJson('/api/settings/reminders', [
                'progress_reminder_day' => 'INVALID_DAY',
            ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'errors' => ['progress_reminder_day'],
            ]);
    }

    /**
     * Test valid progress reminder day is accepted.
     */
    public function test_valid_progress_reminder_day_is_accepted(): void
    {
        // First create settings
        $this->withHeader('Authorization', "Bearer {$this->token}")
            ->getJson('/api/settings/reminders');

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->putJson('/api/settings/reminders', [
                'progress_reminder_day' => 'DIMANCHE',
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);
    }
}
