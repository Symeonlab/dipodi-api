<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sleep_protocols', function (Blueprint $table) {
            $table->id();
            $table->string('condition_key')->unique();
            $table->string('condition_name_fr');
            $table->string('condition_name_en');
            $table->string('condition_name_ar');
            $table->unsignedTinyInteger('cycles_min');
            $table->unsignedTinyInteger('cycles_max');
            $table->string('total_sleep'); // e.g. "9-10h30"
            $table->string('objective_fr');
            $table->string('objective_en');
            $table->string('objective_ar');
            $table->string('category'); // injury, medical, recovery
            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sleep_protocols');
    }
};
