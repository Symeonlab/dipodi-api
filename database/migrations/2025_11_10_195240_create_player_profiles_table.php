<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('player_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // "PANTHERE (PUISSANT)"
            $table->string('group'); // "GARDIENS", "ATTAQUANTS"
        });
    }
    public function down(): void { Schema::dropIfExists('player_profiles'); }
};
