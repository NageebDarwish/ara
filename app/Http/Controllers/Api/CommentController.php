<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\{CommentRequest};
use App\Repositories\Api\{CommentRepository};
use Illuminate\Http\Request;
use App\Helpers\ExceptionHandlerHelper;

class CommentController extends Controller
{
    protected $repository;
    public function __construct(CommentRepository $repository)
    {
        $this->repository = $repository;
    }


    public function store(CommentRequest $request)
    {
        $data = $request->validated();
        $this->repository->create($data);
        return $this->sendResponse('','Comment added successfully');
    }
    
        public function likeComment(Request $request, $comment_id)
    {
        return ExceptionHandlerHelper::tryCatch(function() use ($request, $comment_id) {
            $data=$this->repository->likeComment($request, $comment_id);
            return $this->sendResponse($data,'Comment liked successfully');
        });
    }

}
