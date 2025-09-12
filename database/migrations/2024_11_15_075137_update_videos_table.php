<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->dropColumn('level');

            $table->foreignId('level_id')->nullable()->constrained('levels')->onDelete('cascade');
            $table->foreignId('guide_id')->nullable()->constrained('guides')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->string('level');

            $table->dropForeign(['level_id']);
            $table->dropColumn('level_id');
            
            $table->dropForeign(['guide_id']);
            $table->dropColumn('guide_id');
        });
    }
};
