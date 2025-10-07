<?php

namespace App\Repositories\Api;

use App\Models\Series;
use App\Models\SeriesVideo;
use App\Models\VideoSeriesTimeline;
use Carbon\Carbon;
use App\Models\Goal;
use App\Services\BadgeAssignmentService;

class SeriesRepository
{
    private $model;
     private $badgeService;

    public function __construct(Series $model,BadgeAssignmentService $badgeService)
    {
        $this->model = $model;
        $this->badgeService = $badgeService;
    }

    public function index($request = null)
    {
        $perPage = $request ? (int)($request->input('per_page')) ?: 15 : 15; // fallback used only when paginating

        $query = $this->model->with([
            'country',
            'videos' => function($q) {
                // Only show videos that are free or premium, not 'new'
                $q->whereIn('plan', ['free', 'premium'])
                  ->where('status', 'public');
            },
            'videos.comments.user',
            'videos.comments.likes'
        ]);
        $query->where('status','public');
        $query->whereNotNull('level_id');

        // Only paginate when page or per_page is explicitly provided
        if ($request && ($request->has('page') || $request->has('per_page'))) {
            return $query->paginate($perPage);
        }

        return $query->get();
    }
     public function show($id)
    {
        return $this->model->with([
            'videos' => function($q) {
                // Only show videos that are free or premium, not 'new'
                $q->whereIn('plan', ['free', 'premium'])
                  ->where('status', 'public');
            }
        ])->findOrFail($id);
    }

  public function addToWatched($id)
    {
        $video = SeriesVideo::findOrFail($id);
        $timeLine= VideoSeriesTimeline::where('series_video_id', $id)
            ->where('user_id', auth()->id())
            ->first();
        $duration=$video->duration_seconds ?? 0;
        if($timeLine && $timeLine->progress_time >= $duration){
            $timeLine->update([
                'is_completed' => false,
                'progress_time' => "0",
                'date' => now(),
            ]);
        }else{
            if($timeLine){
            $timeLine->update([
                'is_completed' => true,
                'watched_time' => $duration,
                'progress_time' => $duration,
                'date' => now(),
            ]);
            }else{
                $timeLine=VideoSeriesTimeline::create([
                    'user_id' => auth()->id(),
                    'series_video_id' => $id,
                    'watched_time' => $duration,
                    'progress_time' => $duration,
                    'date' => now(),
                    'is_completed' => true,
                    'series_id'=>$video->series_id,
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

                if ($video->created_at->diffInHours(now()) <= 24 && $timeLine->is_completed == 1) {
                    $this->badgeService->assignSpecialAchievementBadge('first_ray');
                }

                /* handle progress badge */
                $completedSeriesCount = $this->getCompletedSeriesCount(auth()->id());
                $this->badgeService->assignProgressBadge($completedSeriesCount);

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
   public function hideWatchedVideo($id)
    {
        return $this->model::query()
            ->with([
                'country',
                'topic',
                'videos' => function($query) {
                    $query->whereIn('plan', ['free', 'premium']) // Only show free/premium videos
                        ->where('status', 'public')
                        ->whereDoesntHave('timeline', function ($q) {
                            $q->where('user_id', auth()->id())
                            ->where('is_completed', true);
                        })
                        ->with(['comments.user', 'comments.likes']);
                }
            ])
            ->where('id', $id)
            ->where('status', 'public')
            ->whereNotNull('level_id')
            ->firstOrFail();
    }
}
