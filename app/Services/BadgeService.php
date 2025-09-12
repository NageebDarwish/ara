<?php

namespace App\Services;

use App\Models\User;
use App\Models\ProgressBadge;
use App\Models\LearningBadge;
use App\Models\ConsistencyBadge;
use App\Models\CommunityBadge;
use App\Models\SpecialAchievementBadge;
use App\Models\Badge;

class BadgeService
{
    public function assignProgressBadge(User $user, $series)
    {
        $badge=Badge::where('type','progress')->where('series_count',$series)->first();
        $user->badges()->syncWithoutDetaching([$badge->id]);
        return true;
    }

    public function assignLearningBadge(User $user, $hours)
    {
        $badge=Badge::where('type','learning')->where('hours_watched',$hours)->first();
        $user->badges()->syncWithoutDetaching([$badge->id]);
        return true;
    }

    public function assignConsistencyBadge(User $user, $days)
    {
        $badge=Badge::where('type','consistency')->where('streak_days',$days)->first();
        $user->badges()->syncWithoutDetaching([$badge->id]);
        return true;
    }

    public function assignSpecialAchievementBadge(User $user, $name)
    {
        $badge=Badge::where('type','special_achievement')->where('name',$name)->first();
        $user->badges()->syncWithoutDetaching([$badge->id]);
        return true;
    }

  public function assignCommunityBadge(User $user, $name)
    {
        $badge=Badge::where('type','community')->where('name',$name)->first();
        if (!$user->badges()->where('badge_id', $badge->id)->exists()) {
            $user->badges()->syncWithoutDetaching([$badge->id]);
        }
        return true;
    }

    protected function getBadgeModel($badgeType)
    {
        switch ($badgeType) {
            case 'progress':
                return ProgressBadge::class;
            case 'learning':
                return LearningBadge::class;
            case 'consistency':
                return ConsistencyBadge::class;
            case 'community':
                return CommunityBadge::class;
            case 'special':
                return SpecialAchievementBadge::class;
            default:
                return null;
        }
    }
}
