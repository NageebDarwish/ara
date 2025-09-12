<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{VideoTimeline, VideoSeriesTimeline, SeriesVideo, Video};
use App\Traits\ResponseTrait;
use App\Helpers\ExceptionHandlerHelper;
use App\Services\BadgeAssignmentService;

class VideoTimelineController extends Controller
{
    protected $badgeService;

    public function __construct(BadgeAssignmentService $badgeService)
    {
        $this->badgeService = $badgeService;
    }

    public function store(Request $request)
    {
        return ExceptionHandlerHelper::tryCatch(function() use($request){
            $validatedData = $request->validate([
                'video_id' => 'nullable|integer|exists:videos,id',
                'series_video_id' => 'nullable|integer|exists:series_videos,id',
                'watched_time' => 'required|min:0',
                'progress_time' => 'required|min:0',
                'date' => 'nullable|date',
                'type' => 'required|in:video,series',
                'is_completed' => 'required',
                'series_id'  => 'nullable'
            ]);
            if($request->type == 'video')
            {
               $videoTimeline = VideoTimeline::where('user_id', auth()->id())
                    ->where('video_id', $validatedData['video_id'])
                    ->first();

                if ($videoTimeline) {
                    $videoTimeline->update([
                        'watched_time' => $videoTimeline->watched_time + $validatedData['watched_time'],
                        'progress_time' => $validatedData['progress_time'],
                        'is_completed' => $validatedData['is_completed'],
                    ]);
                } else {
                    VideoTimeline::create([
                        'user_id' => auth()->id(),
                        'video_id' => $validatedData['video_id'],
                        'watched_time' => $validatedData['watched_time'],
                        'progress_time' => $validatedData['progress_time'],
                        'is_completed' => $validatedData['is_completed'],
                    ]);
                }
                /* assign special acheivement badge */
                $video = Video::find($validatedData['video_id']);
                if ($video->created_at->diffInHours(now()) <= 24 && $validatedData['is_completed'] == 1) {
                    $this->badgeService->assignSpecialAchievementBadge('first_ray');
                }
                $this->checkAndAssignEclipseViewerBadge(auth()->id());
                /* handle learning badge */
                $totalHours = $this->getTotalWatchingHours(auth()->id());
                $this->badgeService->assignLearningBadge($totalHours);

            }
            if($request->type == 'series')
            {
                $seriesTimeline = VideoSeriesTimeline::where('user_id', auth()->id())
                    ->where('series_id', $validatedData['series_id'])
                    ->where('series_video_id', $validatedData['series_video_id'])
                    ->first();

                if ($seriesTimeline) {
                    $seriesTimeline->update([
                        'watched_time' => $seriesTimeline->watched_time + $validatedData['watched_time'],
                        'progress_time' => $validatedData['progress_time'],
                        'is_completed' => $validatedData['is_completed'],
                    ]);
                } else {
                    VideoSeriesTimeline::create([
                        'user_id' => auth()->id(),
                        'series_id' => $validatedData['series_id'],
                        'series_video_id' => $validatedData['series_video_id'],
                        'watched_time' => $validatedData['watched_time'],
                        'progress_time' => $validatedData['progress_time'],
                        'is_completed' => $validatedData['is_completed'],
                    ]);
                }
                /* assign special acheivement badge */
                $video = SeriesVideo::find($validatedData['series_video_id']);
                if ($video->created_at->diffInHours(now()) <= 24 && $validatedData['is_completed'] == 1) {
                    $this->badgeService->assignSpecialAchievementBadge('first_ray');
                }

                /* handle progress badge */
                $completedSeriesCount = $this->getCompletedSeriesCount(auth()->id());
                $this->badgeService->assignProgressBadge($completedSeriesCount);
            }

            $user=auth()->user();
            $user->watching_hours +=  $validatedData['watched_time'];
            $user->total_watching_hours +=  $validatedData['watched_time'];
            $user->save();
            $this->updateLevel($user);
            return $this->sendResponse('','TimeLine Updated');
        });
    }

    public function updateLevel($user)
    {
        $hours = $user->watching_hours / 3600;

        if ($hours >= 700) {
            $user->progress_level_id = 10;
        } elseif ($hours >= 600) {
            $user->progress_level_id = 9;
        } elseif ($hours >= 500) {
            $user->progress_level_id = 8;
        } elseif ($hours >= 400) {
            $user->progress_level_id = 7;
        } elseif ($hours >= 300) {
            $user->progress_level_id = 6;
        } elseif ($hours >= 200) {
            $user->progress_level_id = 5;
        } elseif ($hours >= 100) {
            $user->progress_level_id = 4;
        } elseif ($hours >= 50) {
            $user->progress_level_id = 3;
        } elseif ($hours >= 20) {
            $user->progress_level_id = 2;
        } else {
            $user->progress_level_id = 1;
        }

        $user->save();
    }
    public function getCompletedSeriesCount($userId)
    {
        $completedSeriesCount = VideoSeriesTimeline::select('series_id')
                                ->where('user_id', $userId)
                                ->where('is_completed', true)
                                ->groupBy('series_id')
                                ->havingRaw('COUNT(series_video_id) = (SELECT COUNT(*) FROM series_videos WHERE series_videos.series_id = video_series_timelines.series_id)')
                                ->count();
        return $completedSeriesCount;
    }

    public function getTotalWatchingHours($userId)
    {
        $totalVideoSeconds = VideoTimeline::where('user_id', $userId)->sum('watched_time');
        $totalSeriesSeconds = VideoSeriesTimeline::where('user_id', $userId)->sum('watched_time');
        $totalSeconds = $totalVideoSeconds + $totalSeriesSeconds;
        $totalHours = $totalSeconds / 3600;
        return $totalHours;
    }

    protected function checkAndAssignEclipseViewerBadge($userId)
    {
        $totalSuperBeginnerVideos = Video::whereHas('level', function ($query) {
                                        $query->where('name', 'Absolute Beginner');
                                    })->count();

        $completedSuperBeginnerVideos = VideoTimeline::where('user_id', $userId)
                                    ->whereHas('video', function ($query) {
                                        $query->whereHas('level', function ($levelQuery) {
                                            $levelQuery->where('name', 'Absolute Beginner');
                                        });
                                    })
                                    ->where('is_completed', true)
                                    ->count();
        if ($completedSuperBeginnerVideos == $totalSuperBeginnerVideos) {
            $this->badgeService->assignSpecialAchievementBadge('eclipse_viewer');
        }
    }

    protected function checkAndAssignCulturalBadges($userId)
    {
        $completedCultureVideos = VideoSeriesTimeline::where('user_id', $userId)
                    ->whereHas('seriesVideo', function ($query) {
                        $query->whereHas('level', function ($levelQuery) {
                            $levelQuery->where('title', 'Culture');
                        });
                    })
                    ->where('is_completed', true)
                    ->count();

        if ($completedCultureVideos >= 100) {
            $this->badgeService->assignSpecialAchievementBadge('cultural_ambassador');
        } elseif ($completedCultureVideos >= 50) {
            $this->badgeService->assignSpecialAchievementBadge('cultural_explorer');
        }
    }



    public function userTimelineVideos()
    {
        return ExceptionHandlerHelper::tryCatch(function(){
                $data=VideoTimeLine::where('user_id',auth()->id())->with('video')->get();
                return $this->sendResponse($data,'Timeline Videos');
        });

    }

     public function userSeriesTimelineVideos()
    {
        return ExceptionHandlerHelper::tryCatch(function(){
                $data=VideoSeriesTimeline::where('user_id',auth()->id())->with('seriesVideo')->get();
                return $this->sendResponse($data,'Timeline Videos');
        });

    }

}