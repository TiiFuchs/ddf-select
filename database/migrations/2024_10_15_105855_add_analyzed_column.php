<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('albums', function (Blueprint $table) {
            $table->boolean('analyzed')->default(false)
                ->after('ignore');
        });
    }

    public function down(): void
    {
        Schema::table('albums', function (Blueprint $table) {
            $table->removeColumn('analyzed');
        });
    }
};
