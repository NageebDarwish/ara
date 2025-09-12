<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Helpers\ExceptionHandlerHelper;
use App\Repositories\Api\SeriesVideoListRepository;
use App\Http\Requests\Api\SeriesVideoListRequest;

class SeriesVideoListController extends Controller
{
    use ResponseTrait;
    protected $repository;

    public function __construct(SeriesVideoListRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        return ExceptionHandlerHelper::tryCatch(function(){
            $data=$this->repository->index();
            return $this->sendResponse($data,'Video list');
        });
    }

    public function store(SeriesVideoListRequest $request)
    {
        return ExceptionHandlerHelper::tryCatch(function() use($request){
            $data=$this->repository->store($request->series_video_id);
            return $this->sendResponse($data,'Added to video list successfully');
        });

    }

    public function remove(SeriesVideoListRequest $request)
    {
        return ExceptionHandlerHelper::tryCatch(function() use($request){
            $data=$this->repository->remove($request->series_video_id);
            return $this->sendResponse($data,'Removed from video list successfully');
        });

    }
}