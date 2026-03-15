<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prophetic_remedies', function (Blueprint $table) {
            $table->id();
            $table->string('condition_key'); // e.g. "toux", "migraine", "depression"
            $table->string('condition_name_fr');
            $table->string('condition_name_en');
            $table->string('condition_name_ar');
            $table->string('element_name_fr'); // e.g. "Nigelle"
            $table->string('element_name_en');
            $table->string('element_name_ar');
            $table->text('mechanism_fr'); // how it works
            $table->text('mechanism_en');
            $table->text('mechanism_ar');
            $table->text('recipe_fr'); // how to use
            $table->text('recipe_en');
            $table->text('recipe_ar');
            $table->text('notes')->nullable();
            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index('condition_key');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prophetic_remedies');
    }
};
