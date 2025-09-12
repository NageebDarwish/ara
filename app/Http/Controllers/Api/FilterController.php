<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Helpers\ExceptionHandlerHelper;
use App\Models\{Video, Series, Topic, Level, Guide};

class FilterController extends Controller
{
    public function video_filter(Request $request)
    {
        return ExceptionHandlerHelper::tryCatch(function() use($request){
            $validated = $request->validate([
                'topic_ids' => 'array|nullable',
                'topic_ids.*' => 'exists:topics,id',
                'guide_ids' => 'array|nullable',
                'guide_ids.*' => 'exists:guides,id',
                'level_ids' => 'array|nullable',
                'level_ids.*' => 'exists:levels,id',
                'sort' => 'nullable|string|in:asc,desc,shortest,longest' ,
                 'hide_watched'=>'nullable|boolean',
            ]);

            $query = Video::query();

            if (!empty($validated['topic_ids'])) {
                $query->whereIn('topic_id', $validated['topic_ids']);
            }

            if (!empty($validated['guide_ids'])) {
                $query->whereIn('guide_id', $validated['guide_ids']);
            }

            if (!empty($validated['level_ids'])) {
                $query->whereIn('level_id', $validated['level_ids']);
            }
            if (!empty($validated['sort'])) {
                switch ($validated['sort']) {
                    case 'asc':
                    case 'desc':
                        // Sort by created_at (asc or desc)
                        $query->orderBy('publishedAt', $validated['sort']);
                        break;
                }
            }

             if (!empty($validated['sort'])) {
                    if ($validated['sort'] === 'shortest' || $validated['sort'] === 'longest') {
                       $query->orderBy('duration_seconds', $validated['sort'] === 'shortest' ? 'asc' : 'desc');
                    }
                }

        $query->where('status','public');
        $query->whereIn('plan',['free','premium']);
        $query->whereNotNull('level_id');
        $query->whereNotNull('guide_id');
        $query->whereNotNull('topic_id');
        
         if($validated['hide_watched'] === 1) {
            $query->whereDoesntHave('timelines', function ($q) {
                $q->where('user_id', auth()->id())
                  ->where('is_completed', true);   
            });
        }

            $videos = $query->get();




            return $this->sendResponse($videos,'All FIlter Data');
        });
    }

    private function convertYouTubeDurationToSeconds($duration)
    {
        try {
            $interval = new \DateInterval($duration);
            return ($interval->h * 3600) + ($interval->i * 60) + $interval->s;
        } catch (\Exception $e) {
            return PHP_INT_MAX; // If parsing fails, place it at the end
        }
    }

    public function series_filter(Request $request)
    {
        return ExceptionHandlerHelper::tryCatch(function() use($request){
            $validated = $request->validate([
                'topic_ids' => 'array|nullable',
                'topic_ids.*' => 'exists:topics,id',
                'guide_ids' => 'array|nullable',
                'guide_ids.*' => 'exists:guides,id',
                'level_ids' => 'array|nullable',
                'level_ids.*' => 'exists:levels,id',
            ]);

            $query = Series::query();

            if (!empty($validated['topic_ids'])) {
                $query->whereIn('topic_id', $validated['topic_ids']);
            }

            if (!empty($validated['guide_ids'])) {
                $query->whereIn('guide_id', $validated['guide_ids']);
            }

            if (!empty($validated['level_ids'])) {
                $query->whereIn('level_id', $validated['level_ids']);
            }

            $videos = $query->get();

            return $this->sendResponse($videos,'All FIlter Data');
        });
    }

    public function levels()
    {
        return ExceptionHandlerHelper::tryCatch(function(){
            $levels = Level::all();
            return $this->sendResponse($levels,'All Levels');
        });
    }

    public function topics()
    {
        return ExceptionHandlerHelper::tryCatch(function(){
            $topics = Topic::all();
            return $this->sendResponse($topics,'All Topics');
        });
    }

    public function guides()
    {
        return ExceptionHandlerHelper::tryCatch(function(){
            $guides = Guide::all();
            return $this->sendResponse($guides,'All Guides');
        });
    }


    public function videoSearch(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string',
        ]);

        $query = Video::query();

        if ($request->has('title') && $request->title) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }
        $query->where('status','public');
        $query->whereIn('plan',['free','premium']);
        $query->whereNotNull('level_id');
        $query->whereNotNull('guide_id');
        $query->whereNotNull('topic_id');


        $result = $query->get();

        return $this->sendResponse($result, 'Search results fetched successfully');
    }


    public function suggestions()
    {
        return ExceptionHandlerHelper::tryCatch(function () {
            $videos = Video::select('title')->get();
            return $this->sendResponse($videos, 'All Suggestions');
        });
    }

}
