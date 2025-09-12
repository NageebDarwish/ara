<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Helpers\ExceptionHandlerHelper;
use App\Repositories\Api\VideoRepository;

class VideoController extends Controller
{
    use ResponseTrait;
    protected $repository;

    public function __construct(VideoRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        return ExceptionHandlerHelper::tryCatch(function() use($request){
            $data=$this->repository->index($request);
            return $this->sendResponse($data,'All videos');
        });
    }
    
     public function hideWatchedVideo(Request $request)
    {
        return ExceptionHandlerHelper::tryCatch(function() use($request) {
            $data=$this->repository->hideWatchedVideo($request);
            return $this->sendResponse($data,'All videos');
        }); 
    }
    
      public function addToWatched($id)
    {
        return ExceptionHandlerHelper::tryCatch(function() use($id) {
            $data=$this->repository->addToWatched($id);
            return $this->sendResponse($data,'Video marked as watched');
        });
    }
         public function videoHistory()
    {
        $data=$this->repository->videoHistory();
        return $this->sendResponse($data,'Videos history fetched');
    }
}