<?php

namespace App\Repositories\Api;

use App\Models\DownloadList;
use App\Models\Video;
use App\Models\SeriesVideo;


class DownloadListRepository
{
    private $model;

    public function __construct(DownloadList $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        return auth()->user()->downloadLists()->with('downloadable')->get();
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
        $user->downloadLists()->create([
            'downloadable_id' => $video->id,
            'downloadable_type' => get_class($video),
        ]);

        return true;
    }

}