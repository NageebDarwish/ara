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
            // Add video_id as nullable foreign key (will be populated by data migration)
            $table->foreignId('video_id')->nullable()->constrained('videos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('series_videos', function (Blueprint $table) {
            $table->dropForeign(['video_id']);
            $table->dropColumn('video_id');
        });
    }
};
