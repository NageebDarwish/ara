<?php

namespace App\Repositories\Api;

use App\Models\Comment;
use App\Models\Video;
use App\Models\SeriesVideo;
use App\Services\BadgeAssignmentService;

class CommentRepository
{
    private $model;
    private $badgeAssignmentService;

    public function __construct(Comment $model,BadgeAssignmentService $badgeAssignmentService)
    {
        $this->model = $model;
         $this->badgeAssignmentService = $badgeAssignmentService;
    }

    public function create(array $data)
    {
        $video=null;
        if($data['type']==='video')
        {
            $video=Video::findOrFail($data['video_id']);
        }elseif($data['type']==='series')
        {
            $video=SeriesVideo::findOrFail($data['video_id']);
        }
        $video->comments()->create([
            'comment'=>$data['comment'],
            'user_id'=>auth()->id(),
        ]);
        $this->checkCommunityBadge();
        return $video;
    }
    
     protected function checkCommunityBadge()
    {
        $userId = auth()->id();
        $comments=$this->model->where('user_id', $userId)->count();
        if($comments>=1)
        {
            $this->badgeAssignmentService->assignCommunityBadge($userId, $comments, 0, 0, 0);
        }
    }
    
    public function likeComment($request, $comment_id)
    {
        $result=$this->model->findOrFail($comment_id)->likes()->updateOrCreate(
            [
                'user_id' => auth()->id(),
            ],
            [
                'is_liked' => ($request->is_like == 1) ? 1 : 0,
            ]
        );
        return $result;
    }


}