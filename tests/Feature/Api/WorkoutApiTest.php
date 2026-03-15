<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\UserProfile;
use App\Models\UserProgress;
use App\Models\WorkoutSession;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class WorkoutApiTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private string $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        UserProfile::create([
            'user_id' => $this->user->id,
            'position' => 'Attaquant',
            'training_location' => 'SI MUSCULATION ET CARDIO EN SALLE',
            'discipline' => 'FOOTBALL',
        ]);
        $this->token = $this->user->createToken('test-token')->plainTextToken;
    }

    /**
     * Test user cannot generate plan without complete profile.
     */
    public function test_user_cannot_generate_plan_without_complete_profile(): void
    {
        // Create user without position
        $user = User::factory()->create();
        UserProfile::create(['user_id' => $user->id]);
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/workout-plan/generate');

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
            ]);
    }

    /**
     * Test user can get their weekly plan.
     */
    public function test_user_can_get_weekly_plan(): void
    {
        // Create a workout session
        WorkoutSession::create([
            'user_id' => $this->user->id,
            'day' => 'LUNDI',
            'theme' => 'Musculation',
            'warmup' => '10 min échauffement',
        ]);

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->getJson('/api/workout-plan');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
            ])
            ->assertJson([
                'success' => true,
            ]);
    }

    /**
     * Test weekly plan response is cached.
     */
    public function test_weekly_plan_is_cached(): void
    {
        WorkoutSession::create([
            'user_id' => $this->user->id,
            'day' => 'LUNDI',
            'theme' => 'Musculation',
        ]);

        Cache::flush();

        // First request - should cache
        $this->withHeader('Authorization', "Bearer {$this->token}")
            ->getJson('/api/workout-plan');

        $cacheKey = "workout_plan:user_{$this->user->id}";
        $this->assertTrue(Cache::has($cacheKey));
    }

    /**
     * Test cache can be refreshed.
     */
    public function test_cache_can_be_refreshed(): void
    {
        WorkoutSession::create([
            'user_id' => $this->user->id,
            'day' => 'LUNDI',
            'theme' => 'Test Theme',
        ]);

        // First request
        $this->withHeader('Authorization', "Bearer {$this->token}")
            ->getJson('/api/workout-plan');

        // Update the session
        WorkoutSession::where('user_id', $this->user->id)->update(['theme' => 'Updated Theme']);

        // Request with refresh parameter
        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->getJson('/api/workout-plan?refresh=true');

        $response->assertStatus(200);
        // The response should have the updated theme
        $this->assertStringContainsString('Updated Theme', $response->getContent());
    }

    /**
     * Test user can log progress.
     */
    public function test_user_can_log_progress(): void
    {
        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->postJson('/api/user-progress', [
                'date' => '2026-01-16',
                'weight' => 75.5,
                'waist' => 80,
                'mood' => 'Excellent',
                'notes' => 'Great workout today!',
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Progress logged successfully',
            ]);

        $this->assertDatabaseHas('user_progress', [
            'user_id' => $this->user->id,
            'date' => '2026-01-16',
            'weight' => 75.5,
        ]);
    }

    /**
     * Test progress logging requires date.
     */
    public function test_progress_logging_requires_date(): void
    {
        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->postJson('/api/user-progress', [
                'weight' => 75.5,
            ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'errors' => ['date'],
            ]);
    }

    /**
     * Test progress logging validates weight range.
     */
    public function test_progress_logging_validates_weight(): void
    {
        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->postJson('/api/user-progress', [
                'date' => '2026-01-16',
                'weight' => 500, // Above max of 300
            ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'errors' => ['weight'],
            ]);
    }

    /**
     * Test user can get their progress logs.
     */
    public function test_user_can_get_progress_logs(): void
    {
        UserProgress::create([
            'user_id' => $this->user->id,
            'date' => '2026-01-15',
            'weight' => 75,
        ]);
        UserProgress::create([
            'user_id' => $this->user->id,
            'date' => '2026-01-16',
            'weight' => 74.5,
        ]);

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->getJson('/api/user-progress');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonCount(2, 'data');
    }

    /**
     * Test progress logging updates existing entry for same date.
     */
    public function test_progress_updates_existing_entry(): void
    {
        // Create initial entry
        UserProgress::create([
            'user_id' => $this->user->id,
            'date' => '2026-01-16',
            'weight' => 75,
        ]);

        // Update with new weight
        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->postJson('/api/user-progress', [
                'date' => '2026-01-16',
                'weight' => 74,
            ]);

        $response->assertStatus(201);

        // Should only have one entry
        $this->assertEquals(1, UserProgress::where('user_id', $this->user->id)->count());

        // Should have updated weight
        $this->assertDatabaseHas('user_progress', [
            'user_id' => $this->user->id,
            'date' => '2026-01-16',
            'weight' => 74,
        ]);
    }
}
