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
        Schema::create('series_videos', function (Blueprint $table) {
            $table->id();
            $table->string('playlist_id')->nullable();
            $table->string('title');
            $table->longText('description')->nullable();
            $table->enum('plan', ['new','free', 'premium'])->default('new');
            $table->string('video');
            $table->foreignId('series_id')->constrained()->onDelete('cascade');
            $table->string('publishedAt')->nullable();
            $table->string('scheduleDateTime')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('series_videos');
    }
};