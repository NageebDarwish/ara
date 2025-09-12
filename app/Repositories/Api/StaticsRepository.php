<?php

namespace App\Repositories\Api;

use App\Models\User;
use App\Models\PostComment;
use App\Models\PostLike;


class StaticsRepository
{

    public function index()
    {
        $premiumUsers=User::where('is_premium',true)->count();
        $topActiveUsers = User::withCount(['givenPostLikes', 'givenPostComments','givenVideoComments'])
            ->orderByRaw('given_post_likes_count + given_post_comments_count + given_video_comments_count DESC')
            ->limit(3)
            ->get();
        $data=[
            'premium_users'=>$premiumUsers,
            'top_active_users'=>$topActiveUsers,
        ];
        return $data;

    }


}