<?php

namespace App\Services;

use App\Models\User;
use App\Services\BadgeService;
use Illuminate\Support\Facades\Log;

class BadgeAssignmentService
{
    protected $badgeService;

    public function __construct(BadgeService $badgeService)
    {
        $this->badgeService = $badgeService;
    }

    public function assignProgressBadge($seriesCompleted)
    {
        $user = auth()->user();

        if ($seriesCompleted >= 100) {
            $this->badgeService->assignProgressBadge($user, '100');
        } elseif ($seriesCompleted >= 50) {
            $this->badgeService->assignProgressBadge($user, '50');
        } elseif ($seriesCompleted >= 10) {
            $this->badgeService->assignProgressBadge($user, '10');
        } elseif ($seriesCompleted >= 1) {
            $this->badgeService->assignProgressBadge($user, '1');
        }
    }

    public function assignLearningBadge($totalHours)
    {
        $user = auth()->user();

        if ($totalHours >= 1000) {
            $this->badgeService->assignLearningBadge($user, '1000');
        } elseif ($totalHours >= 500) {
            $this->badgeService->assignLearningBadge($user, '500');
        } elseif ($totalHours >= 100) {
            $this->badgeService->assignLearningBadge($user, '100');
        } elseif ($totalHours >= 20) {
            $this->badgeService->assignLearningBadge($user, '20');
        } elseif ($totalHours >= 1) {
            $this->badgeService->assignLearningBadge($user, '1');
        }
    }

    public function assignConsistencyBadge($streakDays)
    {
        $user = auth()->user();

        if ($streakDays >= 365) {
            $this->badgeService->assignConsistencyBadge($user, 'daily');
        } elseif ($streakDays >= 100) {
            $this->badgeService->assignConsistencyBadge($user, '100');
        } elseif ($streakDays >= 30) {
            $this->badgeService->assignConsistencyBadge($user, days: '30');
        } elseif ($streakDays >= 7) {
            $this->badgeService->assignConsistencyBadge($user, days: '7');
        } elseif ($streakDays >= 3) {
            $this->badgeService->assignConsistencyBadge($user, '3');
        }
    }

   public function assignCommunityBadge($userId, $commentsPosted, $likesGiven, $likesReceived,$posts)
    {
        $user = User::find($userId);

        if ($commentsPosted >= 1) {
            $this->badgeService->assignCommunityBadge($user, 'The First Spark');
        }
        if ($likesReceived >= 100) {
            $this->badgeService->assignCommunityBadge($user, 'Constellation Connector');
        }
        if($posts >= 50){
            $this->badgeService->assignCommunityBadge($user, 'Desert Star');
        }
    }

    public function assignSpecialAchievementBadge($condition)
    {
        $user = auth()->user();
        Log::info($user);

        switch ($condition) {
            case 'first_ray':
                $this->badgeService->assignSpecialAchievementBadge($user, 'First Ray'); // First Ray
                break;
            case 'eclipse_viewer':
                $this->badgeService->assignSpecialAchievementBadge($user, 'Eclipse Viewer'); // Eclipse Viewer
                break;
            case 'cultural_ambassador':
                $this->badgeService->assignSpecialAchievementBadge($user, 'Cultural Ambassador'); // Cultural Explorer
                break;
            case 'cultural_explorer': 
                $this->badgeService->assignSpecialAchievementBadge($user, 'Cultural Explorer'); // Cultural Ambassador
                break;
            case 'Eternal Light':
                $this->badgeService->assignSpecialAchievementBadge($user, 'Eternal Light'); // Eternal Light
                break;
        }
    }
}