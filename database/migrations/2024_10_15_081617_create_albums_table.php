<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('albums', function (Blueprint $table) {
            $table->id();
            $table->string('am_id')->unique();
            $table->string('name');
            $table->integer('track_count');
            $table->date('release_date')->nullable();
            $table->string('url');
            $table->json('artwork');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('albums');
    }
};
