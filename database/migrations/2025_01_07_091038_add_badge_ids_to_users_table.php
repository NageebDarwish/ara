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
        Schema::table('users', function (Blueprint $table) {
            // Adding nullable badge ID columns for different badge categories
            $table->unsignedBigInteger('progress_badge_id')->nullable()->after('remember_token');
            $table->unsignedBigInteger('learning_badge_id')->nullable()->after('progress_badge_id');
            $table->unsignedBigInteger('consistency_badge_id')->nullable()->after('learning_badge_id');
            $table->unsignedBigInteger('special_achievement_id')->nullable()->after('consistency_badge_id');

            // Optionally, add foreign key constraints if a 'badges' table exists
            $table->foreign('progress_badge_id')->references('id')->on('progress_badges')->onDelete('set null');
            $table->foreign('learning_badge_id')->references('id')->on('learning_badges')->onDelete('set null');
            $table->foreign('consistency_badge_id')->references('id')->on('consistency_badges')->onDelete('set null');
            $table->foreign('special_achievement_id')->references('id')->on('special_achievement_badges')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['progress_badge_id']);
            $table->dropForeign(['learning_badge_id']);
            $table->dropForeign(['consistency_badge_id']);
            $table->dropForeign(['special_achievement_id']);

            $table->dropColumn([
                'progress_badge_id',
                'learning_badge_id',
                'consistency_badge_id',
                'special_achievement_id',
            ]);
        });
    }
};
