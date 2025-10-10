<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\SeriesVideo;
use App\Models\Video;
use App\Models\Series;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Migrate data from series_videos to videos table
        $seriesVideos = SeriesVideo::all();

        foreach ($seriesVideos as $seriesVideo) {
            // Check if a video already exists with this YouTube video ID
            $existingVideo = Video::where('video', $seriesVideo->video)->first();

            if ($existingVideo) {
                // If video exists, just link to it
                $seriesVideo->video_id = $existingVideo->id;
                $seriesVideo->save();
            } else {
                // Create a new video record from series_video data
                // Get series data for level_id and country_id
                $series = Series::find($seriesVideo->series_id);

                $video = Video::create([
                    'title' => $seriesVideo->title,
                    'description' => $seriesVideo->description,
                    'video' => $seriesVideo->video,
                    'plan' => $seriesVideo->plan,
                    'level_id' => $series ? $series->level_id : null,
                    'guide_id' => null, // Series don't have guide_id
                    'topic_id' => null, // Series don't have topic_id, can be set manually later if needed
                    'country_id' => $series ? $series->country_id : null,
                    'publishedAt' => $seriesVideo->publishedAt,
                    'scheduleDateTime' => $seriesVideo->scheduleDateTime,
                    'status' => $seriesVideo->status,
                    'duration' => $seriesVideo->duration,
                    'duration_seconds' => $seriesVideo->duration_seconds,
                    'is_hide' => $seriesVideo->is_hide ?? false,
                ]);

                // Link series_video to the new video
                $seriesVideo->video_id = $video->id;
                $seriesVideo->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Set all video_id to null in series_videos
        SeriesVideo::query()->update(['video_id' => null]);

        // Optionally, you might want to delete videos that were created from series_videos
        // But this is risky as some videos might have been created independently
        // So we'll just null out the video_id references
    }
};
