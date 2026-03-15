<?php

namespace Tests\Feature\Api;

use App\Models\OnboardingOption;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OnboardingApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test onboarding data endpoint is publicly accessible.
     */
    public function test_onboarding_data_is_publicly_accessible(): void
    {
        $response = $this->getJson('/api/onboarding-data');

        $response->assertStatus(200);
    }

    /**
     * Test onboarding data returns expected structure.
     */
    public function test_onboarding_data_returns_expected_structure(): void
    {
        // Create some test onboarding options
        OnboardingOption::create([
            'type' => 'discipline',
            'name' => 'Football',
        ]);
        OnboardingOption::create([
            'type' => 'position',
            'name' => 'Attaquant',
        ]);
        OnboardingOption::create([
            'type' => 'goal',
            'name' => 'Perdre du poids',
        ]);

        $response = $this->getJson('/api/onboarding-data');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'disciplines',
                'positions',
                'goals',
            ]);
    }

    /**
     * Test onboarding data groups options by type.
     */
    public function test_onboarding_data_groups_by_type(): void
    {
        OnboardingOption::create(['type' => 'discipline', 'name' => 'Football']);
        OnboardingOption::create(['type' => 'discipline', 'name' => 'Basketball']);
        OnboardingOption::create(['type' => 'position', 'name' => 'Attaquant']);

        $response = $this->getJson('/api/onboarding-data');

        $response->assertStatus(200);

        $data = $response->json();

        // Should have 2 disciplines
        $this->assertCount(2, $data['disciplines'] ?? []);
        // Should have 1 position
        $this->assertCount(1, $data['positions'] ?? []);
    }
}
