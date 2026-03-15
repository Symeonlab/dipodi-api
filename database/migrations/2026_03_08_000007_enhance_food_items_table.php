<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('food_items', function (Blueprint $table) {
            $table->decimal('h_plus_1_energy', 5, 1)->nullable()->after('tags'); // H+1 energy value
            $table->decimal('h_plus_24_recovery', 5, 1)->nullable()->after('h_plus_1_energy'); // H+24 recovery value
            $table->string('meal_timing')->nullable()->after('h_plus_24_recovery'); // pre_workout, post_workout, recovery, any
        });
    }

    public function down(): void
    {
        Schema::table('food_items', function (Blueprint $table) {
            $table->dropColumn(['h_plus_1_energy', 'h_plus_24_recovery', 'meal_timing']);
        });
    }
};
