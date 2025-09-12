<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\{PlanRepository};
use Illuminate\Http\Request;
use App\Helpers\ExceptionHandlerHelper;
use App\Traits\ResponseTrait;

class PlanController extends Controller
{
    use ResponseTrait;
    protected $repository;

    public function __construct(PlanRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        return ExceptionHandlerHelper::tryCatch(function() {
            $data = $this->repository->all();
            if(!$data){
                return $this->sendError('Plans not found',404);
            }else
            {
                return $this->sendResponse($data,'All Plans');
            }
        });
    }

}
