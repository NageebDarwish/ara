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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('cycle');
            $table->double('price'); 
            $table->boolean('free_videos');
            $table->boolean('progress_tracking');
            $table->boolean('community_forums');
            $table->boolean('exclusive_videos_added_daily');
            $table->integer('no_of_exclusive_videos_added_daily');
            $table->boolean('premium_video_series');
            $table->boolean('ability_to_watch_videos_offline');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
