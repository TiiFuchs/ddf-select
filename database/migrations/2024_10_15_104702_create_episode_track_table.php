<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('episode_track', function (Blueprint $table) {
            $table->foreignId('episode_id')->constrained()->cascadeOnDelete();
            $table->foreignId('track_id')->constrained()->cascadeOnDelete();

            $table->primary(['episode_id', 'track_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('episode_track');
    }
};
