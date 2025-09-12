<?php

namespace App\Repositories\Api;

use App\Models\VideoHistory;
use App\Models\Video;
use App\Models\SeriesVideo;


class VideoHistoryRepository
{
    private $model;

    public function __construct(VideoHistory $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        return auth()->user()->videoHistories()->with('history')->get();
    }

    public function store($id, $type)
    {
        $user = auth()->user();

        if ($type === 'video') {
            $video = Video::findOrFail($id);
        } elseif ($type === 'series_video') {
            $video = SeriesVideo::findOrFail($id);
        } else {
            throw new \Exception('Invalid video type');
        }

        // Create the download list entry
        $user->videoHistories()->create([
            'history_id' => $video->id,
            'history_type' => get_class($video),
        ]);

        return true;
    }

}