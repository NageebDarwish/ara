<?php

namespace App\Repositories\Api;

use App\Models\Post;
use Illuminate\Support\Facades\Storage;
use App\Helpers\UploadFiles;
use App\Models\PostComment;
use App\Models\PostLike;
use App\Models\PostDisLike;
use App\Models\PostTag;
use App\Helpers\NotificationHelper;
use App\Services\BadgeAssignmentService;
class PostRepository
{
    private $model;
    private $comment;
    private $like;
    private $dislike;
    private $badgeService;

    public function __construct(Post $model,PostComment $comment,PostDisLike $dislike,PostLike $like,BadgeAssignmentService $badgeService)
    {
        $this->model = $model;
        $this->comment=$comment;
        $this->like=$like;
        $this->dislike=$dislike;
        $this->badgeService = $badgeService;
    }

    public function all()
    {
        return $this->model
            ->with([
                'user',
                'tags',
                'comments' => function($query) {
                    $query->whereNull('parent_id')
                        ->with(['user', 'replies.user', 'replies.replies.user']);
                },
                'likes',
                'disLikes',
                'savers'
            ])
            ->where('user_id', auth()->id())
            ->get();
    }

    public function show($id)
    {
        return $this->model
            ->with([
                'user',
                'tags',
                'comments' => function($query) {
                    $query->whereNull('parent_id')
                        ->with(['user', 'replies.user', 'replies.replies.user']);
                },
                'likes',
                'disLikes'
            ])
            ->findOrFail($id);
    }


    public function create(array $data)
    {
        $data['user_id']= auth()->id();
        if (isset($data['file'])) {
            $data['file']  = UploadFiles::upload($data['file'], 'post');
        }
        $post= $this->model->create($data);
         if(isset($data['tags']))
        {
            $post->tags()->attach($data['tags']);
        }
        $this->checkCommunityPostBadge();
        return $post;
    }
    
    
    protected function checkCommunityPostBadge()
    {
        $userId = auth()->id();
        $posts = $this->model->where('user_id', $userId)->count();
        if ($posts >= 50) {
            $this->badgeService->assignCommunityBadge($userId, 0, 0, 0, $posts);
        }
    }


    public function update($id, array $data)
    {
        $model = $this->model->findOrFail($id);
        if (isset($data['file'])) {
            if ($model->file) {
                UploadFiles::delete($model->file, 'post');
            }
            $data['file'] = UploadFiles::upload($data['file'], 'post');
        }
        if(isset($data['tags'])){
            $model->tags()->sync($data['tags']);
        }
        $model->update($data);

        return $model;
    }

    public function delete($id)
    {
        $model = $this->model->findOrFail($id);
        if ($model->file) {
            UploadFiles::delete($model->file, 'post');
        }

        return $model->delete();
    }

    public function addComment($data,$id)
    {
        $result=$this->comment->create([
            'post_id'=>$id,
            'parent_id' => $data['parent_id'] ?? null,
            'comment'=>$data['comment'],
            'user_id'=>auth()->id(),
            ]);
            $this->checkCommunityBadge();
        $data=[
        'post_id'=>$id,
        'user_name'=>auth()->user()->fullname,
        'user_id'=>auth()->id(),
        'parent_id'=>$data['parent_id'] ?? null,
        'created_at'=>$result->created_at,
        'comment'=>$data['comment'],
         'id'=>$result->id,
        ];
        NotificationHelper::triggerEvent('comment','comment_added_'.$id,$data);
        return $result;
    }
      protected function checkCommunityBadge()
    {
        $userId = auth()->id();
        $comments=$this->model->where('user_id', $userId)->count();
        if($comments>=1)
        {
            $this->badgeService->assignCommunityBadge($userId, $comments, 0, 0, 0);
        }
    }
    
    public function addLike($id)
    {
        $like = $this->like->updateOrCreate(
            [
                'user_id' => auth()->id(),
                'post_id' => $id,
            ],
            [
                'is_liked' => \DB::raw('NOT is_liked')
            ]
        )->fresh();
        $this->checkCommunityLikeBadge($id);
        $data = [
        'user_id' => auth()->id(),
        'post_id' => $id,
        'is_liked' => (bool) $like->is_liked,
        ];
        NotificationHelper::triggerEvent('like', 'like_added_'.$id, $data);

        return $like;
    }
     protected function checkCommunityLikeBadge($id)
    {
        $post = $this->model->findOrFail($id);
        $user = $post->user;
        $likesReceived = $post->likes()->count();
        $this->badgeService->assignCommunityBadge($user->id, 0, 0, $likesReceived, 0);
    }
    public function addDisLike($id)
    {
        $dislike=$this->dislike->updateOrCreate(
            [
                'user_id'=>auth()->id(),
                'post_id'=>$id,
            ],
            [
            'is_dis_liked'=>\DB::raw('NOT is_dis_liked')
            ]);
        $data = [
       'user_id' => auth()->id(),
        'post_id' => $id,
        'is_dis_liked' => (bool) $dislike->is_dis_liked,
        ];
        NotificationHelper::triggerEvent('dis_like','dis_like_added_'.$id,$data);
        return $dislike;
    }

    public function getTags()
    {
        return PostTag::all();
    }

    public function getAll()
    {
        return $this->model
        ->with([
            'user',
            'tags',
            'comments' => function($query) {
                $query->whereNull('parent_id')
                    ->with(['user', 'replies.user', 'replies.replies.user']);
            },
            'likes',
            'disLikes'])
        ->orderBy('created_at','desc')->get();
    }
    public function getMyAnswers()
    {
       return $this->model
        ->with([
            'user',
            'tags',
            'likes',
            'disLikes',
            'comments' => function($query) {
                $query->whereNull('parent_id')
                    ->with(['user', 'replies.user', 'replies.replies.user']);
            }
        ])
        ->whereHas('comments', function ($query) {
            $query->where('user_id', auth()->id());
        })
        ->get();
    }
    public function saved()
    {
        return auth()->user()->savedPosts()->with([
            'user',
            'tags',
            'comments' => function($query) {
                $query->whereNull('parent_id')
                    ->with(['user', 'replies.user', 'replies.replies.user']);
            },
            'likes',
            'disLikes',
            'savers'
        ])
        ->get();
    }
   public function saveUnsave($post_id)
    {
        $post = $this->model->findOrFail($post_id);
        $user = auth()->user();
        $wasSaved = $user->savedPosts()->where('post_id', $post_id)->exists();
        $user->savedPosts()->toggle($post->id);
        $isNowSaved = !$wasSaved;
        
        return [
            'post' => $post,
            'action' => $isNowSaved ? 'saved' : 'unsaved'
        ];
    }
    public function getPostsByTag($tag_id)
    {
        return PostTag::with(['posts' => function($query) {
            $query->with([
                'user',
                'tags',
                'comments' => function($query) {
                $query->whereNull('parent_id')
                    ->with(['user', 'replies.user', 'replies.replies.user']);
            },
            'likes',
            'disLikes']);
        }])
        ->where('id', $tag_id)
        ->first();
    }

    public function latestPosts()
    {
        return $this->model
            ->with([
                'user',
                'tags',
                'comments' => function($query) {
                    $query->whereNull('parent_id')
                        ->with(['user', 'replies.user', 'replies.replies.user']);
                },
                'likes',
                'disLikes'
            ])
            ->latest()
            ->take(5)
            ->get();
    }

    public function likeComment($request, $comment_id)
    {
        $result=$this->comment->findOrFail($comment_id)->likes()->updateOrCreate(
            [
                'user_id' => auth()->id(),
            ],
            [
                'is_liked' => ($request->is_like == 1) ? 1 : 0,
            ]
        )->fresh();
        $data=[
        'comment_id'=>$comment_id,
        'user_id' => auth()->id(),
        'user_name'=>auth()->user()->fullname,
        'is_liked'=>$result->is_liked,
        ];
        NotificationHelper::triggerEvent('comment_like','comment_like_added_'.$comment_id,$data);
        return $result;
    }
}