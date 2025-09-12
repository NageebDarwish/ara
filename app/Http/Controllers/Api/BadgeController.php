<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\BadgeAssignmentService;

class BadgeController extends Controller
{
    protected $badgeAssignmentService;

    public function __construct(BadgeAssignmentService $badgeAssignmentService)
    {
        $this->badgeAssignmentService = $badgeAssignmentService;
    }

    public function assignBadge(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'badge_type' => 'required|in:progress,learning,consistency,community,special',
            'badge_data' => 'required|array',
        ]);

        $userId = $validatedData['user_id'];
        $badgeType = $validatedData['badge_type'];
        $badgeData = $validatedData['badge_data'];

        switch ($badgeType) {
            case 'progress':
                $this->badgeAssignmentService->assignProgressBadge($userId, $badgeData['series_completed']);
                break;
            case 'learning':
                $this->badgeAssignmentService->assignLearningBadge($userId, $badgeData['hours_watched']);
                break;
            case 'consistency':
                $this->badgeAssignmentService->assignConsistencyBadge($userId, $badgeData['streak_days']);
                break;
            case 'community':
                $this->badgeAssignmentService->assignCommunityBadge(
                    $userId,
                    $badgeData['comments_posted'] ?? 0,
                    $badgeData['likes_given'] ?? 0,
                    $badgeData['likes_received'] ?? 0
                );
                break;
            case 'special':
                $this->badgeAssignmentService->assignSpecialAchievementBadge($userId, $badgeData['condition']);
                break;
        }

        return response()->json(['message' => 'Badge assigned successfully']);
    }
}
