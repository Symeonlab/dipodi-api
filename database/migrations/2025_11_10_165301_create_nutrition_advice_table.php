<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nutrition_advice', function (Blueprint $table) {
            $table->id();
            $table->string('condition_name'); // e.g., "SURPOIDS/OBÉSITÉ", "DIABÈTE"
            $table->json('foods_to_avoid')->nullable(); // ["Viande", "Desserts", "Fromage"]
            $table->json('foods_to_eat')->nullable(); // ["Légumes", "Quinoa"]
            $table->text('prophetic_advice_fr')->nullable(); // "jus de fruits / les jus de dattes..." [cite: 1951]
            $table->text('prophetic_advice_en')->nullable();
            $table->text('prophetic_advice_ar')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nutrition_advice');
    }
};
