<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('food_items', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Filet de poulet", "Riz complet"
            $table->string('category'); // e.g., "plat", "accompagnement", "dessert"
            $table->json('tags')->nullable(); // ["viande", "feculent", "fruit"]
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('food_items');
    }
};
