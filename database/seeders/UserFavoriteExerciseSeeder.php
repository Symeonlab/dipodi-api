<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Exercise;
use Illuminate\Support\Facades\DB;

class UserFavoriteExerciseSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('user_favorite_exercises')->truncate();

        $users = User::all();
        $kineExercises = Exercise::where('category', 'LIKE', 'KINE%')->get();

        if ($kineExercises->count() < 3) {
            // Not enough exercises to favorite
            return;
        }

        foreach ($users as $user) {
            // Get 3 random Kine exercises
            $favorites = $kineExercises->random(3);

            foreach ($favorites as $exercise) {
                // Manually create the link in the pivot table
                DB::table('user_favorite_exercises')->insert([
                    'user_id' => $user->id,
                    'exercise_id' => $exercise->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
