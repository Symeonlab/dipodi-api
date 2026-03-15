<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserProfileApiTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private string $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        // Create user profile (normally done by User model boot method)
        UserProfile::create(['user_id' => $this->user->id]);
        $this->token = $this->user->createToken('test-token')->plainTextToken;
    }

    /**
     * Test authenticated user can view their profile.
     */
    public function test_user_can_view_profile(): void
    {
        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->getJson('/api/user');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id',
                    'name',
                    'email',
                    'profile',
                ],
            ])
            ->assertJson([
                'success' => true,
            ]);
    }

    /**
     * Test unauthenticated user cannot view profile.
     */
    public function test_unauthenticated_user_cannot_view_profile(): void
    {
        $response = $this->getJson('/api/user');

        $response->assertStatus(401);
    }

    /**
     * Test user can update their profile.
     */
    public function test_user_can_update_profile(): void
    {
        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->putJson('/api/user/profile', [
                'name' => 'Updated Name',
                'discipline' => 'FOOTBALL',
                'position' => 'Attaquant',
                'height' => 180,
                'weight' => 75,
                'age' => 25,
                'gender' => 'HOMME',
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Profile updated successfully',
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'name' => 'Updated Name',
        ]);

        $this->assertDatabaseHas('user_profiles', [
            'user_id' => $this->user->id,
            'discipline' => 'FOOTBALL',
            'position' => 'Attaquant',
            'is_onboarding_complete' => true,
        ]);
    }

    /**
     * Test profile update validates height range.
     */
    public function test_profile_update_validates_height(): void
    {
        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->putJson('/api/user/profile', [
                'height' => 50, // Below minimum of 100
            ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'errors' => ['height'],
            ]);
    }

    /**
     * Test profile update validates weight range.
     */
    public function test_profile_update_validates_weight(): void
    {
        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->putJson('/api/user/profile', [
                'weight' => 500, // Above maximum of 300
            ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'errors' => ['weight'],
            ]);
    }

    /**
     * Test profile update validates age range.
     */
    public function test_profile_update_validates_age(): void
    {
        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->putJson('/api/user/profile', [
                'age' => 10, // Below minimum of 16
            ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'errors' => ['age'],
            ]);
    }

    /**
     * Test profile can have nutrition preferences.
     */
    public function test_profile_can_have_nutrition_preferences(): void
    {
        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->putJson('/api/user/profile', [
                'is_vegetarian' => true,
                'goal' => 'PERDRE DU POIDS',
                'activity_level' => 'ACTIF',
                'breakfast_preferences' => ['cereales', 'fruits'],
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->user->refresh();
        $this->assertTrue($this->user->profile->is_vegetarian);
        $this->assertEquals('PERDRE DU POIDS', $this->user->profile->goal);
    }
}
