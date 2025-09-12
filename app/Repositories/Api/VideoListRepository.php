<?php

namespace App\Repositories\Api;

use App\Models\VideoList;



class VideoListRepository
{
    private $model;

    public function __construct(VideoList $model)
    {
        $this->model = $model;
    }

     public function index()
    {
        $data['videos']=auth()->user()->videoLists()->with('video')->get();
        $data['series_videos']=auth()->user()->seriesVideoLists()->with('video')->get();
        return $data;
    }

    public function store($id)
    {
        $user = auth()->user();

        
        $user->videoLists()->updateOrCreate(
            ['video_id' => $id], 
            ['video_id' => $id]  
        );
        
        return true;
    }
    
     public function remove($id)
    {
        $video=$this->model->where('video_id', $id)->where('user_id', auth()->id())->first();
        $video->delete();
        return true;
    }

}