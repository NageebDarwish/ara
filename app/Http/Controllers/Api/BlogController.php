<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Api\{BlogRepository};
use Illuminate\Http\Request;
use App\Helpers\ExceptionHandlerHelper;
use App\Traits\ResponseTrait;

class BlogController extends Controller
{
    protected $repository;
     use ResponseTrait;
    public function __construct(BlogRepository $repository)
    {
        $this->repository = $repository;
    }

   public function index()
    {
        return ExceptionHandlerHelper::tryCatch(function(){
            $data['latest']=$this->repository->latest();
            $data['all'] = $this->repository->all();
            return $this->sendResponse($data,'All Blogs');
        });

    }
    
    public function show($id)
    {
        return ExceptionHandlerHelper::tryCatch(function() use ($id){
            $data['blog'] = $this->repository->find($id);
            $data['related'] = $this->repository->related($id);
            return $this->sendResponse($data,'Blog Details');
        });
    }
    
    public function categories()
    {
        return ExceptionHandlerHelper::tryCatch(function() {
            $data= $this->repository->categories();
            return $this->sendResponse($data,'All categories');
        });
    }

     public function categoryBlogs(Request $request)
    {
        return ExceptionHandlerHelper::tryCatch(function() use ($request){
            $request->validate([
                'ids'=>'required|array',
            ]);
            $data = $this->repository->categoryAll($request->ids);
            return $this->sendResponse($data,'Blog Details');
        });

    }

}
