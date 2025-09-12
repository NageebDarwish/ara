<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WatchedVideo;
use App\Traits\ResponseTrait;
use App\Helpers\ExceptionHandlerHelper;
use App\Http\Requests\Api\OutsidePlatformRequest;
use App\Repositories\Api\OutsidePlatFormRepository;

class OutsidePlatFormController extends Controller
{
    use ResponseTrait;
    protected $repository;

    public function __construct(OutsidePlatFormRepository $repository)
    {
        $this->repository = $repository;
    }


    public function index()
    {
        return ExceptionHandlerHelper::tryCatch(function(){
            $data=$this->repository->index();
            return $this->sendResponse($data,'All Records');
        });
    }


    public function store(OutsidePlatformRequest $request)
    {
        return ExceptionHandlerHelper::tryCatch(function() use($request){
            $data=$this->repository->store($request->validated());
            return $this->sendResponse($data,'Reocrds Created Successfully');
        });
    }


    public function delete($id)
    {
        return ExceptionHandlerHelper::tryCatch(function() use($id){
            $data=$this->repository->delete($id);
            return $this->sendResponse($data,'Record deleted successfully');
        });
    }


    public function update(Request $request, $id)
    {
        return ExceptionHandlerHelper::tryCatch(function() use($request, $id){
            $data=$this->repository->update($request->all(), $id);
            return $this->sendResponse($data,'Record updated successfully');
        });
    }
}
