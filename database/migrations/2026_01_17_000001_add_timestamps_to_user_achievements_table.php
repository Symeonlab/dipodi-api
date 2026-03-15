<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds created_at and updated_at columns to user_achievements pivot table
     * to fix the "Column not found: 1054 Unknown column 'user_achievements.created_at'" error
     * caused by the Achievement model's withTimestamps() on the BelongsToMany relationship.
     */
    public function up(): void
    {
        Schema::table('user_achievements', function (Blueprint $table) {
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_achievements', function (Blueprint $table) {
            $table->dropTimestamps();
        });
    }
};
