<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Helpers\ExceptionHandlerHelper;
use App\Repositories\Api\SeriesRepository;

class SeriesController extends Controller
{
    use ResponseTrait;
    protected $repository;

    public function __construct(SeriesRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        return ExceptionHandlerHelper::tryCatch(function() use($request){
            $data=$this->repository->index($request);
            return $this->sendResponse($data,'All series');
        });
    }
     public function show($id)
    {
        return ExceptionHandlerHelper::tryCatch(function() use($id){
            $data=$this->repository->show($id);
            return $this->sendResponse($data,'Series details');
        });
    }

     public function addToWatched($id)
    {
        return ExceptionHandlerHelper::tryCatch(function() use($id) {
            $data=$this->repository->addToWatched($id);
            return $this->sendResponse($data,'Video marked as watched');
        });
    }
     public function hideWatchedVideo($id)
    {
        return ExceptionHandlerHelper::tryCatch(function() use($id) {
            $data=$this->repository->hideWatchedVideo($id);
            return $this->sendResponse($data,'All videos');
        });
    }
}
