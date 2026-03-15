<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Health Assessment Tables
     * Based on the QUESTIONNAIRE tab in DIPODDI PROGRAMME.xlsx
     * Covers: Energy, Recovery, Injuries, Digestion, Performance, Hydration, Psychology, etc.
     */
    public function up(): void
    {
        // Categories for health assessment questions
        Schema::create('health_assessment_categories', function (Blueprint $table) {
            $table->id();
            $table->string('key', 100)->unique();
            $table->string('name_fr');
            $table->string('name_en');
            $table->string('name_ar')->nullable();
            $table->string('icon', 50)->nullable();
            $table->string('discipline')->nullable(); // football, fitness, or null for all
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Health assessment questions
        Schema::create('health_assessment_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('health_assessment_categories')->cascadeOnDelete();
            $table->string('subcategory')->nullable(); // e.g., "FATIGUE", "CRAMPES", "TENDINITES"
            $table->text('question_fr');
            $table->text('question_en');
            $table->text('question_ar')->nullable();
            $table->enum('answer_type', ['yes_no', 'scale', 'multiple_choice', 'text'])->default('yes_no');
            $table->json('answer_options')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // User health assessment sessions
        Schema::create('health_assessment_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->uuid('session_uuid')->unique();
            $table->enum('status', ['in_progress', 'completed'])->default('in_progress');
            $table->integer('total_questions')->default(0);
            $table->integer('answered_questions')->default(0);
            $table->json('health_insights')->nullable(); // Generated insights based on answers
            $table->json('recommendations')->nullable(); // Nutrition/training recommendations
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });

        // User health assessment answers
        Schema::create('health_assessment_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('health_assessment_sessions')->cascadeOnDelete();
            $table->foreignId('question_id')->constrained('health_assessment_questions')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('answer_value');
            $table->timestamps();

            $table->unique(['session_id', 'question_id']);
            $table->index(['user_id', 'question_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('health_assessment_answers');
        Schema::dropIfExists('health_assessment_sessions');
        Schema::dropIfExists('health_assessment_questions');
        Schema::dropIfExists('health_assessment_categories');
    }
};
