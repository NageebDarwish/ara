<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Helpers\ExceptionHandlerHelper;
use App\Repositories\Api\GoalRepository;
use App\Http\Requests\Api\GoalRequest;

class GoalController extends Controller
{
    use ResponseTrait;
    protected $repository;

    public function __construct(GoalRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        return ExceptionHandlerHelper::tryCatch(function(){
            $data=$this->repository->index();
            return $this->sendResponse($data,'All goals');
        });
    }


    public function store(GoalRequest $request)
    {
        return ExceptionHandlerHelper::tryCatch(function() use($request){
            $goal=$request->validated();
            $data=$this->repository->store($goal);
            return $this->sendResponse($data,'Added to goal successfully');
        });
    }


    public function update(Request $request)
    {
        return ExceptionHandlerHelper::tryCatch(function() use($request){
            $data=$this->repository->update($request->all());
            return $this->sendResponse($data,'Goal updated successfully');
        });

    }

    public function streaks()
    {
        return ExceptionHandlerHelper::tryCatch(function(){
            $data=$this->repository->streaks();
            return $this->sendResponse($data,'All Streaks');
        });
    }

    public function showAll()
    {
        return ExceptionHandlerHelper::tryCatch(function(){
            $data=$this->repository->showAll();
            return $this->sendResponse($data,'All goals');
        });
    }

}