<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{WatchedVideo, HideVideo, Video, SeriesVideo};
use App\Traits\ResponseTrait;
use App\Helpers\ExceptionHandlerHelper;
use DB;

class VideoCountController extends Controller
{
    public function videoCount()
    {
        $data = WatchedVideo::where('user_id', auth()->user()->id)->first();

        return $this->sendResponse(isset($data->video_count) ? $data->video_count : [],'Watched Video Count');
    }

  public function storeVideoCount(Request $request)
    {
        $data = WatchedVideo::where('user_id', auth()->user()->id)->first();
        if($data)
        {
            if($request->type==='add')
            {
                $data->video_count += 1;
                $data->save();
            }elseif($request->type==='remove'){
                $data->video_count -= 1;
                if($data->video_count < 0) {
                    $data->video_count = 0;
                }
                $data->save();
            }

        }else
        {
            if($request->type==='add')
            {
                WatchedVideo::create([
                    'user_id' => auth()->user()->id,
                    'video_count' => 1,
                ]);
            }elseif($request->type==='remove'){
                WatchedVideo::create([
                    'user_id' => auth()->user()->id,
                    'video_count' => 0,
                ]);
            }
        }

        return $this->sendResponse('','Record Created Successfully');
    }

    public function is_hide(Request $request)
    {
        $request->validate([
            'type' => 'required|in:video,series', 
            'video_id' => 'nullable|exists:videos,id', 
            'series_video_id' => 'nullable|exists:series_videos,id', 
        ]);

        if($request->type == 'video')
        {
            $video = HideVideo::updateOrCreate([
                'user_id' => auth()->user()->id,
                'video_id'=> $request->video_id
            ]);
        }
        if($request->type == 'series')
        {
            $video = HideVideo::updateOrCreate([
                'user_id' => auth()->user()->id,
                'series_video_id'=> $request->series_video_id
            ]);
        }

        return $this->sendResponse('', 'Record updated successfully');
    }


    public function index(Request $request)
    {
        $request->validate([
            'type' => 'required|in:video,series'
        ]);

        if($request->type == 'video')
        {
            $hideVideos = HideVideo::where('user_id', auth()->user()->id)
                ->where('video_id', '!=', null)
                ->pluck('video_id') 
                ->toArray();

            $videos = Video::whereNotIn('id', $hideVideos)->get(); 

            $result['all_videos'] = $videos;
            $result['hide_videos'] = HideVideo::where('user_id', auth()->user()->id)
                ->where('video_id', '!=', null)
                ->with('video')
                ->get();

            return $this->sendResponse($result, 'Record updated successfully');

        }

        if($request->type == 'series')
        {
            $hiddenSeriesVideos = HideVideo::where('user_id', auth()->user()->id)
                ->where('series_video_id', '!=', null)
                ->pluck('series_video_id') 
                ->toArray();

            $videoSeries = SeriesVideo::whereNotIn('id', $hiddenSeriesVideos)->get(); 

            $result['all_video_series'] = $videoSeries; 
            $result['hide_video_series'] = HideVideo::where('user_id', auth()->user()->id)
                ->where('series_video_id', '!=', null)
                ->with('seriesVideo') 
                ->get();

            return $this->sendResponse($result, 'Record updated successfully');
        }
    }
}
