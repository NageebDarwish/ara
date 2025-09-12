<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\{PostRequest};
use App\Repositories\Api\{PostRepository};
use Exception;
use Illuminate\Http\Request;
use App\Helpers\ExceptionHandlerHelper;


class PostController extends Controller
{
    protected $repository;

    public function __construct(PostRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        return ExceptionHandlerHelper::tryCatch(function(){
            $data = $this->repository->all();
            return $this->sendResponse($data,'All posts');
        });
    }

    public function show($id)
    {
        return ExceptionHandlerHelper::tryCatch(function() use($id){
            $data = $this->repository->show($id);
            return $this->sendResponse($data,'Post details');
        });
    }

    public function store(PostRequest $request)
    {
        return ExceptionHandlerHelper::tryCatch(function() use($request){
            $data = $request->validated();
            $this->repository->create($data);
            return $this->sendResponse($data,'Post created successfully');
        });
    }


    public function update(Request $request, $id)
    {
        return ExceptionHandlerHelper::tryCatch(function() use($request,$id){
            $data = $request->all();
            $this->repository->update($id, $data);
            return $this->sendResponse($data,'Updated successfully');
        });
    }

    public function destroy($id)
    {
        return ExceptionHandlerHelper::tryCatch(function() use($id){
            $this->repository->delete($id);
            return $this->sendResponse('success','Deleted successfully');
        });
    }

    public function addComment(Request $request,$id)
    {
        return ExceptionHandlerHelper::tryCatch(function() use($request,$id){
            $data=$request->validate([
                'comment'=>'required',
                'parent_id'=>'nullable',
                'user_id'=>'nullable',
            ]);
            $this->repository->addComment($data,$id);
            return $this->sendResponse('success','Comment added successfully');
        });
    }

       public function addLike($id)
    {
        return ExceptionHandlerHelper::tryCatch(function() use($id){
            $this->repository->addLike($id);
            return $this->sendResponse('success','Like added successfully');
        });
    }

       public function addDisLike($id)
    {
        return ExceptionHandlerHelper::tryCatch(function() use($id){
            $this->repository->addDisLike($id);
            return $this->sendResponse('success','Dis Like added successfully');
        });
    }

    public function getTags()
    {
        return ExceptionHandlerHelper::tryCatch(function() {
            $data=$this->repository->getTags();
            return $this->sendResponse($data,'All tags for posts');
        });
    }

    public function getAll()
    {
        return ExceptionHandlerHelper::tryCatch(function() {
            $data=$this->repository->getAll();
            return $this->sendResponse($data,'All posts');
        });
    }
    public function myAnswers()
    {
        return ExceptionHandlerHelper::tryCatch(function() {
            $data=$this->repository->getMyAnswers();
            return $this->sendResponse($data,'All My Answers Posts');
        });
    }
    public function savedPosts()
    {
        return ExceptionHandlerHelper::tryCatch(function() {
            $data=$this->repository->saved();
            return $this->sendResponse($data,'All My Saved Posts');
        });
    }
  public function saveUnsavePost($post_id)
    {
        return ExceptionHandlerHelper::tryCatch(function() use ($post_id) {
            $result = $this->repository->saveUnsave($post_id);
            
            $message = $result['action'] === 'saved' 
                ? 'Post saved successfully' 
                : 'Post removed from saved items';
                
            return $this->sendResponse([
                'post' => $result['post'],
                'is_saved' => $result['action'] === 'saved'
            ], $message);
        });
    }
    public function getPostsByTag($tag_id)
    {
        return ExceptionHandlerHelper::tryCatch(function() use ($tag_id) {
            $data=$this->repository->getPostsByTag($tag_id);
            return $this->sendResponse($data,'Post saved/unsaved successfully');
        });
    }

    public function latetstPosts()
    {
        return ExceptionHandlerHelper::tryCatch(function() {
            $data=$this->repository->latestPosts();
            return $this->sendResponse($data,'Latest posts');
        });
    }

    public function likeComment(Request $request, $comment_id)
    {
        return ExceptionHandlerHelper::tryCatch(function() use ($request, $comment_id) {
            $data=$this->repository->likeComment($request, $comment_id);
            return $this->sendResponse($data,'Comment liked successfully');
        });
    }
}
