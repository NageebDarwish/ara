<?php

namespace App\Repositories\Api;

use App\Models\SeriesVideoList;



class SeriesVideoListRepository
{
    private $model;

    public function __construct(SeriesVideoList $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        return auth()->user()->seriesVideoLists()->with('video')->get();
    }

    public function store($id)
    {
        $user = auth()->user();
        $user->seriesVideoLists()->updateOrCreate(
            ['series_video_id' => $id],
            ['series_video_id' => $id]
        );

        return true;
    }
    public function remove($id)
    {
        $video=$this->model->where('series_video_id', $id)->where('user_id', auth()->id())->first();
        $video->delete();
        return true;
    }

}