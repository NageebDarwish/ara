<?php

namespace App\Repositories\Api;


use App\Models\Video;
use Carbon\Carbon;
use App\Models\VideoTimeline;
use App\Models\SeriesVideo;
use App\Models\Goal;
use App\Services\BadgeAssignmentService;
use App\Models\VideoSeriesTimeline;

class VideoRepository
{
   private $model;
    private $badgeService;

    public function __construct(Video $model,BadgeAssignmentService $badgeService)
    {
        $this->model = $model;
        $this->badgeService = $badgeService;
    }

    public function index($request)
    {
        $perPage = (int)($request->input('per_page')) ?: 15; // fallback used only when paginating

        $query = $this->model::where('publishedAt', '<=', Carbon::now()->toISOString());
        $query->where('status','public');
        $query->whereIn('plan',['free','premium']);
        $query->whereNotNull('level_id');
        $query->whereNotNull('guide_id');
        $query->whereNotNull('topic_id');
        if ($request->has('topic')) {
            $query->whereHas('topic', function ($q) use ($request) {
                $q->where('name', $request->input('topic'));
            });
        }
        $query->with(['comments.user','comments.likes']);
        $query->orderBy('updated_at', 'desc');

        // Only paginate when page or per_page is explicitly provided
        if ($request->has('page') || $request->has('per_page')) {
            return $query->paginate($perPage);
        }

        return $query->get();
    }

      public function hideWatchedVideo($request)
    {
       $query = $this->model::where('publishedAt', '<=', Carbon::now()->toISOString());
        $query->where('status','public');
        $query->whereIn('plan',['free','premium']);
        $query->whereNotNull('level_id');
        $query->whereNotNull('guide_id');
        $query->whereNotNull('topic_id');
        if ($request->has('topic')) {
            $query->whereHas('topic', function ($q) use ($request) {
                $q->where('name', $request->input('topic'));
            });
        }
        $query->with(['comments.user','comments.likes']);
        $query->whereDoesntHave('timelines', function ($q) {
            $q->where('user_id', auth()->id())
              ->where('is_completed', true);   // exclude completed videos
        });
        $query->orderBy('updated_at', 'desc');

        return $query->get();
    }

     public function addToWatched($id)
    {
        $video = $this->model::findOrFail($id);
        $timeLine= VideoTimeline::where('video_id', $id)
            ->where('user_id', auth()->id())
            ->first();
        $duration=$video->duration_seconds ?? 0;

        if($timeLine && $timeLine->progress_time >= $duration){
            $timeLine->update([
                'is_completed' => false,
                'progress_time' => "0",
                'date' => now(),
            ]);
        }
        else
        {
            if($timeLine){
            $timeLine->update([
                'is_completed' => true,
                'watched_time' => $duration,
                'progress_time' => $duration,
                'date' => now(),
            ]);
            }else{
                $timeLine=VideoTimeline::create([
                    'user_id' => auth()->id(),
                    'video_id' => $id,
                    'watched_time' => $duration,
                    'progress_time' => $duration,
                    'date' => now(),
                    'is_completed' => true,
                ]);

            }

            $goal=Goal::where('user_id', auth()->id())
                ->where('date', now()->toDateString())
                ->first();
            if ($goal) {
                $goal->update([
                    'completed_minutes' => $goal->completed_minutes + $duration,
                ]);
            }

                if ($video->created_at->diffInHours(now()) <= 24 && $$timeLine->is_completed == 1) {
                    $this->badgeService->assignSpecialAchievementBadge('first_ray');
                }
                $this->checkAndAssignEclipseViewerBadge(auth()->id());
                /* handle learning badge */
                $totalHours = $this->getTotalWatchingHours(auth()->id());
                $this->badgeService->assignLearningBadge($totalHours);

            $user=auth()->user();
            $user->watching_hours +=  $duration;
            $user->total_watching_hours +=  $duration;
            $user->save();
            $this->updateLevel($user);

        }

        return $timeLine;
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
    public function getTotalWatchingHours($userId)
    {
        $totalVideoSeconds = VideoTimeline::where('user_id', $userId)->sum('watched_time');
        $totalSeriesSeconds = VideoSeriesTimeline::where('user_id', $userId)->sum('watched_time');
        $totalSeconds = $totalVideoSeconds + $totalSeriesSeconds;
        $totalHours = $totalSeconds / 3600;
        return $totalHours;
    }

     public function videoHistory()
    {
        $user = auth()->user();
        $data['videos'] = Video::whereHas('timelines', function($q) use ($user) {
                $q->where('user_id', $user->id)
                ->orderBy('updated_at', 'desc');
            })
            ->with(['timelines' => function($q) use ($user) {
                $q->where('user_id', $user->id)
                ->orderBy('updated_at', 'desc');
            }])
            ->take(5)
            ->get();
        $data['series_videos'] = SeriesVideo::whereHas('timeline', function($q) use ($user) {
                $q->where('user_id', $user->id)
                ->orderBy('updated_at', 'desc');
            })
            ->with(['timeline' => function($q) use ($user) {
                $q->where('user_id', $user->id)
                ->orderBy('updated_at', 'desc');
            }])
            ->take(5)
            ->get();

        return $data;
    }
}
