<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates tables for the feedback system based on DIPODDI PROGRAMME FEED BACK sheet
     */
    public function up(): void
    {
        // Feedback categories table
        Schema::create('feedback_categories', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // e.g., 'football_goalkeeper', 'nutrition_weight_loss'
            $table->string('name_fr');
            $table->string('name_en')->nullable();
            $table->string('name_ar')->nullable();
            $table->string('icon')->nullable(); // SF Symbol name
            $table->string('discipline')->nullable(); // 'football', 'fitness', null for universal
            $table->string('position')->nullable(); // 'goalkeeper', 'defender', etc.
            $table->string('goal')->nullable(); // 'weight_loss', 'muscle_gain', etc.
            $table->boolean('requires_injury')->default(false);
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['discipline', 'is_active']);
            $table->index(['goal', 'is_active']);
        });

        // Feedback questions table
        Schema::create('feedback_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('feedback_categories')->onDelete('cascade');
            $table->text('question_fr');
            $table->text('question_en')->nullable();
            $table->text('question_ar')->nullable();
            $table->enum('answer_type', ['scale', 'yes_no', 'text', 'multi'])->default('scale');
            $table->json('answer_options')->nullable(); // For multi-choice questions
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['category_id', 'is_active', 'sort_order']);
        });

        // Feedback sessions table (groups answers from one session)
        Schema::create('feedback_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained('feedback_categories')->onDelete('cascade');
            $table->string('session_uuid')->unique();
            $table->integer('total_questions')->default(0);
            $table->integer('answered_questions')->default(0);
            $table->decimal('average_score', 4, 2)->nullable();
            $table->enum('status', ['in_progress', 'completed', 'abandoned'])->default('in_progress');
            $table->json('insights')->nullable(); // AI-generated insights
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['user_id', 'category_id']);
            $table->index(['user_id', 'created_at']);
        });

        // Individual feedback answers
        Schema::create('feedback_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('feedback_sessions')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('feedback_questions')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('answer_value'); // Stores: "1"-"10" for scale, "yes"/"no", or text
            $table->timestamps();

            $table->unique(['session_id', 'question_id']);
            $table->index(['user_id', 'question_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback_answers');
        Schema::dropIfExists('feedback_sessions');
        Schema::dropIfExists('feedback_questions');
        Schema::dropIfExists('feedback_categories');
    }
};
