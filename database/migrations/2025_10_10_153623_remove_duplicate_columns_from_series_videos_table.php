<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('series_videos', function (Blueprint $table) {
            // Remove duplicate columns that are now stored in the videos table
            $table->dropColumn([
                'title',
                'description',
                'video',
                'publishedAt',
                'scheduleDateTime',
                'status',
                'duration',
                'duration_seconds'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('series_videos', function (Blueprint $table) {
            // Restore the columns if rolling back
            $table->string('title');
            $table->longText('description')->nullable();
            $table->string('video');
            $table->string('publishedAt')->nullable();
            $table->string('scheduleDateTime')->nullable();
            $table->string('status')->nullable();
            $table->string('duration')->nullable();
            $table->integer('duration_seconds')->nullable();
        });
    }
};
