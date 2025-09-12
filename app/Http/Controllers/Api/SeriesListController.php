<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Api\SeriesListRequest;
use App\Traits\ResponseTrait;
use App\Helpers\ExceptionHandlerHelper;
use App\Repositories\Api\SeriesListRepository;

class SeriesListController extends Controller
{
    use ResponseTrait;
    protected $repository;

    public function __construct(SeriesListRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        return ExceptionHandlerHelper::tryCatch(function(){
            $data=$this->repository->index();
            return $this->sendResponse($data,'Series list');
        });
    }

    public function store(SeriesListRequest $request)
    {
        return ExceptionHandlerHelper::tryCatch(function() use($request){
            $data=$this->repository->store($request->series_id);
            return $this->sendResponse($data,'Added to series list successfully');
        });

    }


}
