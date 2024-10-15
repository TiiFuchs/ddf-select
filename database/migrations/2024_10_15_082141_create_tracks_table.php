<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tracks', function (Blueprint $table) {
            $table->id();
            $table->string('am_id');
            $table->string('name');
            $table->integer('disc_number')->nullable();
            $table->integer('track_number');
            $table->integer('duration_in_millis');
            $table->foreignId('album_id');
            $table->timestamps();

            $table->unique(['album_id', 'disc_number', 'track_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tracks');
    }
};
