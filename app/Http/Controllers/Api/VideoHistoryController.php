<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Helpers\ExceptionHandlerHelper;
use App\Http\Requests\Api\VideoHistoryRequest;
use App\Repositories\Api\VideoHistoryRepository;

class VideoHistoryController extends Controller
{
    use ResponseTrait;
    protected $repository;

    public function __construct(VideoHistoryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        return ExceptionHandlerHelper::tryCatch(function(){
            $data=$this->repository->index();
            return $this->sendResponse($data,'Videos history');
        });
    }

    public function store(VideoHistoryRequest $request)
    {
        return ExceptionHandlerHelper::tryCatch(function() use($request){
            $data=$this->repository->store($request->video_id,$request->type);
            return $this->sendResponse($data,'Added to videos history successfully');
        });
    }

    public function timelineSave(Request $request)
    {
        return ExceptionHandlerHelper::tryCatch(function() use($request){
            $validatedData = $request->validate([
                'video_id' => 'required', 
                'watched_time' => 'required',       
                'date' => 'nullable|date',                       
            ]);

            VideoTimeline::updateOrCreate(
                [
                    'user_id' => 2,
                    'video_id' => $validatedData['video_id']
                ],
                [
                    'watched_time' => $validatedData['watched_time'],
                    'date' => $validatedData['date'] ?? null
                ]
            );

            return $this->sendResponse('','TimeLine Updated');
        });
    }
}