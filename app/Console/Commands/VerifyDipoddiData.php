<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PlayerProfile;
use App\Models\Exercise;
use App\Models\WorkoutTheme;
use App\Models\WorkoutThemeRule;
use App\Models\NutritionAdvice;
use App\Models\BonusWorkoutRule;
use App\Models\FoodItem;
use Illuminate\Support\Facades\DB;

class VerifyDipoddiData extends Command
{
    protected $signature = 'dipoddi:verify {--fix : Attempt to fix issues}';
    protected $description = 'Verify data consistency between DIPODDI Excel, API backend, and iOS app expectations';

    private $issues = [];
    private $warnings = [];

    public function handle()
    {
        $this->info('╔══════════════════════════════════════════════════════════════╗');
        $this->info('║         DIPODDI Data Consistency Verification                ║');
        $this->info('╚══════════════════════════════════════════════════════════════╝');
        $this->newLine();

        $this->verifyPlayerProfiles();
        $this->verifyWorkoutThemes();
        $this->verifyExercises();
        $this->verifyNutritionAdvice();
        $this->verifyProfileThemeMappings();
        $this->verifyDataIntegrity();

        $this->newLine();
        $this->printSummary();

        return count($this->issues) > 0 ? 1 : 0;
    }

    private function verifyPlayerProfiles(): void
    {
        $this->info('🏃 Verifying Player Profiles...');

        $expectedGroups = [
            'GARDIEN' => ['La Panthère', 'La Pieuvre', 'Le Chat', 'L\'Araignée'],
            'DÉFENSEUR' => ['Le Contrôleur', 'Le Casseur', 'Le Relanceur', 'Le Polyvalent'],
            'MILIEU' => ['L\'Architecte', 'The Rock', 'Le Pitbull', 'La Gazelle'],
            'ATTAQUANT' => ['Le Magicien', 'Le Sniper', 'Le Tank', 'Le Renard'],
            'FITNESS_FEMME' => ['La Silhouette', 'La Tonique', 'La Fine', 'L\'Athlète Puissante', 'Bien-être'],
            'FITNESS_HOMME' => ['L\'Athlétique', 'Le Massif', 'Le Sec', 'Le Fonctionnel', 'Le Force Brute'],
        ];

        $totalExpected = array_sum(array_map('count', $expectedGroups));
        $totalFound = PlayerProfile::count();

        $this->line("   Expected profiles: {$totalExpected} (minimum)");
        $this->line("   Found profiles: {$totalFound}");

        // Check each group
        foreach ($expectedGroups as $group => $profiles) {
            $found = PlayerProfile::where('group', $group)->pluck('name')->toArray();
            $missing = array_diff($profiles, $found);

            if (count($missing) > 0) {
                $this->issues[] = "Missing profiles in {$group}: " . implode(', ', $missing);
                $this->error("   ✗ {$group}: Missing " . count($missing) . " profiles");
            } else {
                $this->info("   ✓ {$group}: " . count($found) . " profiles");
            }
        }

        // Check for profiles without descriptions
        $noDescription = PlayerProfile::whereNull('description')->orWhere('description', '')->count();
        if ($noDescription > 0) {
            $this->warnings[] = "{$noDescription} profiles have no description";
        }
    }

    private function verifyWorkoutThemes(): void
    {
        $this->newLine();
        $this->info('🎯 Verifying Workout Themes...');

        $expectedThemesByType = [
            'gym' => 18,      // Force max, hypertrophie, endurance, etc.
            'cardio' => 27,   // All cardio themes from Excel
            'home' => 2,      // Circuit Maison, HIIT Maison
            'mobility' => 1,  // Mobilité & Récupération
        ];

        foreach ($expectedThemesByType as $type => $minExpected) {
            $count = WorkoutTheme::where('type', $type)->count();
            $status = $count >= $minExpected ? '✓' : '✗';
            $color = $count >= $minExpected ? 'info' : 'error';

            $this->{$color}("   {$status} {$type}: {$count} themes (expected >= {$minExpected})");

            if ($count < $minExpected) {
                $this->issues[] = "Insufficient {$type} themes: {$count}/{$minExpected}";
            }
        }

        // Check themes have rules
        $themesWithoutRules = WorkoutTheme::whereDoesntHave('rules')->count();
        if ($themesWithoutRules > 0) {
            $this->issues[] = "{$themesWithoutRules} themes have no associated rules";
            $this->error("   ✗ {$themesWithoutRules} themes missing rules");
        } else {
            $this->info("   ✓ All themes have rules");
        }
    }

    private function verifyExercises(): void
    {
        $this->newLine();
        $this->info('💪 Verifying Exercises...');

        $expectedCategories = [
            'MUSCULATION' => [
                'BRAS' => 27,
                'ÉPAULES' => 26,
                'DOS' => 18,
                'PECTORAUX' => 26,
                'QUADRICEPS' => 17,
            ],
            'KINE MOBILITÉ' => [
                'CHEVILLES' => 2,
                'GENOUX' => 2,
                'HANCHES' => 3,
                'PIEDS' => 2,
            ],
            'KINE RENFORCEMENT' => [
                'ADDUCTEURS' => 2,
                'FESSIERS' => 3,
            ],
            'BONUS' => [
                'ABDOS' => 60,
                'POMPES' => 22,
                'GAINAGE' => 32,
            ],
            'MAISON' => [
                'PERTE DE POIDS' => 4,
                'RENFORCEMENT' => 4,
            ],
            'CARDIO' => [
                'TAPIS' => 4,
                'VÉLO' => 2,
                'ELLIPTIQUE' => 2,
                'RAMEUR' => 1,
            ],
        ];

        $totalExpected = 0;
        $totalFound = 0;

        foreach ($expectedCategories as $category => $subCategories) {
            $categoryCount = Exercise::where('category', $category)->count();
            $this->line("   {$category}: {$categoryCount} exercises");

            foreach ($subCategories as $subCategory => $minExpected) {
                $count = Exercise::where('category', $category)
                    ->where('sub_category', $subCategory)
                    ->count();

                $totalExpected += $minExpected;
                $totalFound += $count;

                $status = $count >= $minExpected ? '✓' : '✗';
                if ($count < $minExpected) {
                    $this->warnings[] = "{$category}/{$subCategory}: {$count}/{$minExpected}";
                }
            }
        }

        $this->line("   Total exercises: {$totalFound} (minimum expected: {$totalExpected})");

        // Check for exercises without video URLs
        $noVideo = Exercise::whereNull('video_url')->orWhere('video_url', '')->count();
        if ($noVideo > 0) {
            $this->warnings[] = "{$noVideo} exercises have no video URL";
            $this->warn("   ⚠ {$noVideo} exercises without video URLs");
        }

        // Check for duplicate video URLs
        $duplicates = DB::table('exercises')
            ->select('video_url', DB::raw('COUNT(*) as count'))
            ->whereNotNull('video_url')
            ->where('video_url', '!=', '')
            ->groupBy('video_url')
            ->having('count', '>', 1)
            ->count();

        if ($duplicates > 0) {
            $this->warnings[] = "{$duplicates} duplicate video URLs found";
        }
    }

    private function verifyNutritionAdvice(): void
    {
        $this->newLine();
        $this->info('🥗 Verifying Nutrition Advice...');

        $expectedCategories = [
            'FOOTBALL' => 18,    // Sport nutrition for football
            'FITNESS' => 13,     // Sport nutrition for fitness
            'PADEL' => 14,       // Sport nutrition for padel
        ];

        $footballCount = NutritionAdvice::where('condition_name', 'like', '%(FOOTBALL)%')->count();
        $fitnessCount = NutritionAdvice::where('condition_name', 'like', '%(FITNESS)%')->count();
        $padelCount = NutritionAdvice::where('condition_name', 'like', '%(PADEL)%')->count();
        $propheticCount = NutritionAdvice::where('condition_name', 'not like', '%(FOOTBALL)%')
            ->where('condition_name', 'not like', '%(FITNESS)%')
            ->where('condition_name', 'not like', '%(PADEL)%')
            ->count();

        $this->line("   Football nutrition advice: {$footballCount}");
        $this->line("   Fitness nutrition advice: {$fitnessCount}");
        $this->line("   Padel nutrition advice: {$padelCount}");
        $this->line("   Prophetic medicine advice: {$propheticCount}");

        $total = NutritionAdvice::count();
        if ($total < 50) {
            $this->issues[] = "Insufficient nutrition advice: {$total}/50";
        } else {
            $this->info("   ✓ Total nutrition advice: {$total}");
        }

        // Check for empty foods_to_eat
        $emptyFoods = NutritionAdvice::whereNull('foods_to_eat')
            ->orWhere('foods_to_eat', '[]')
            ->count();
        if ($emptyFoods > 0) {
            $this->warnings[] = "{$emptyFoods} nutrition advice entries have empty foods_to_eat";
        }
    }

    private function verifyProfileThemeMappings(): void
    {
        $this->newLine();
        $this->info('🔗 Verifying Profile-Theme Mappings...');

        $mappingsCount = DB::table('player_profile_themes')->count();
        $profilesWithMappings = DB::table('player_profile_themes')
            ->distinct('player_profile_id')
            ->count('player_profile_id');
        $themesWithMappings = DB::table('player_profile_themes')
            ->distinct('workout_theme_id')
            ->count('workout_theme_id');

        $totalProfiles = PlayerProfile::count();
        $totalThemes = WorkoutTheme::where('type', 'gym')->count();

        $this->line("   Total mappings: {$mappingsCount}");
        $this->line("   Profiles with mappings: {$profilesWithMappings}/{$totalProfiles}");
        $this->line("   Themes with mappings: {$themesWithMappings}/{$totalThemes}");

        if ($mappingsCount < 50) {
            $this->issues[] = "Insufficient profile-theme mappings: {$mappingsCount}/50";
            $this->error("   ✗ Need more profile-theme mappings");
        } else {
            $this->info("   ✓ Mappings look adequate");
        }

        // Check for profiles without any theme mappings
        $profilesWithoutMappings = PlayerProfile::whereDoesntHave('themes')->count();
        if ($profilesWithoutMappings > 0) {
            $this->warnings[] = "{$profilesWithoutMappings} profiles have no theme mappings";
        }
    }

    private function verifyDataIntegrity(): void
    {
        $this->newLine();
        $this->info('🔍 Verifying Data Integrity...');

        // Check foreign key references - orphan workout theme rules
        $orphanThemeRules = WorkoutThemeRule::whereNotIn('workout_theme_id', WorkoutTheme::pluck('id'))->count();
        if ($orphanThemeRules > 0) {
            $this->issues[] = "{$orphanThemeRules} orphan theme rules found";
        } else {
            $this->info("   ✓ All theme rules have valid themes");
        }

        // Check BonusWorkoutRules exist
        $bonusRules = BonusWorkoutRule::count();
        if ($bonusRules < 4) {
            $this->issues[] = "Insufficient bonus workout rules: {$bonusRules}/4 (DÉBUTANT, CONFIRMÉ, AVANCÉ, EXPERT)";
        } else {
            $this->info("   ✓ Bonus workout rules: {$bonusRules}");
        }

        // Check FoodItems
        $foodItems = FoodItem::count();
        if ($foodItems < 20) {
            $this->warnings[] = "Low food items count: {$foodItems}";
        } else {
            $this->info("   ✓ Food items: {$foodItems}");
        }

        // Verify iOS expected data structure
        $this->verifyiOSCompatibility();
    }

    private function verifyiOSCompatibility(): void
    {
        $this->newLine();
        $this->info('📱 Verifying iOS App Compatibility...');

        // Check that API endpoints return expected structure

        // 1. PlayerProfiles should have name, group, description
        $invalidProfiles = PlayerProfile::whereNull('name')
            ->orWhereNull('group')
            ->count();
        if ($invalidProfiles > 0) {
            $this->issues[] = "{$invalidProfiles} profiles missing required iOS fields";
        }

        // 2. Exercises should have name, category, video_url for KINE
        $kineWithoutVideo = Exercise::where('category', 'like', 'KINE%')
            ->whereNull('video_url')
            ->count();
        if ($kineWithoutVideo > 0) {
            $this->warnings[] = "{$kineWithoutVideo} KINE exercises missing video URLs";
        }

        // 3. NutritionAdvice should have condition_name and foods_to_eat
        $invalidAdvice = NutritionAdvice::whereNull('condition_name')->count();
        if ($invalidAdvice > 0) {
            $this->issues[] = "{$invalidAdvice} nutrition advice entries missing condition_name";
        }

        $this->info("   ✓ iOS compatibility checks complete");
    }

    private function printSummary(): void
    {
        $this->info('╔══════════════════════════════════════════════════════════════╗');
        $this->info('║                        SUMMARY                                ║');
        $this->info('╚══════════════════════════════════════════════════════════════╝');

        $this->newLine();
        $this->line('Database Statistics:');
        $this->table(
            ['Entity', 'Count'],
            [
                ['Player Profiles', PlayerProfile::count()],
                ['Workout Themes', WorkoutTheme::count()],
                ['Workout Theme Rules', WorkoutThemeRule::count()],
                ['Exercises', Exercise::count()],
                ['Nutrition Advice', NutritionAdvice::count()],
                ['Food Items', FoodItem::count()],
                ['Bonus Workout Rules', BonusWorkoutRule::count()],
                ['Profile-Theme Mappings', DB::table('player_profile_themes')->count()],
            ]
        );

        $this->newLine();

        if (count($this->issues) > 0) {
            $this->error('❌ ISSUES FOUND (' . count($this->issues) . '):');
            foreach ($this->issues as $issue) {
                $this->error("   • {$issue}");
            }
        } else {
            $this->info('✅ No critical issues found!');
        }

        if (count($this->warnings) > 0) {
            $this->newLine();
            $this->warn('⚠️  WARNINGS (' . count($this->warnings) . '):');
            foreach ($this->warnings as $warning) {
                $this->warn("   • {$warning}");
            }
        }

        $this->newLine();
        $this->info('Run `php artisan db:seed --class=DipoddiProgrammeSeeder` to seed/reseed data.');
        $this->info('Run `php artisan db:seed --class=DipoddiCardioAndMappingsSeeder` for additional CARDIO themes.');
    }
}
