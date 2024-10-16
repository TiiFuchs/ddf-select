<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('albums', function (Blueprint $table) {
            $table->renameColumn('am_id', 'apple_music_id');
        });

        Schema::table('tracks', function (Blueprint $table) {
            $table->renameColumn('am_id', 'apple_music_id');
        });
    }

    public function down(): void
    {
        Schema::table('albums', function (Blueprint $table) {
            $table->renameColumn('apple_music_id', 'am_id');
        });

        Schema::table('tracks', function (Blueprint $table) {
            $table->renameColumn('apple_music_id', 'am_id');
        });
    }
};
