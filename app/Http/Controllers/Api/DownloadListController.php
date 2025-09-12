<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Helpers\ExceptionHandlerHelper;
use App\Repositories\Api\DownloadListRepository;
use App\Http\Requests\Api\DownloadListRequest;


class DownloadListController extends Controller
{
    use ResponseTrait;
    protected $repository;

    public function __construct(DownloadListRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        return ExceptionHandlerHelper::tryCatch(function(){
            $data=$this->repository->index();
            return $this->sendResponse($data,'Download list');
        });
    }

    public function store(DownloadListRequest $request)
    {
        return ExceptionHandlerHelper::tryCatch(function() use($request){
            $data=$this->repository->store($request->video_id,$request->type);
            return $this->sendResponse($data,'Added to download list successfully');
        });

    }


}