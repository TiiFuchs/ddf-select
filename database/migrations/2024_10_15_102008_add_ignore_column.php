<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('albums', function (Blueprint $table) {
            $table->boolean('ignore')->default(false)
                ->after('artwork');
        });
    }

    public function down(): void
    {
        Schema::table('albums', function (Blueprint $table) {
            $table->removeColumn('ignore');
        });
    }
};
