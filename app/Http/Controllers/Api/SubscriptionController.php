<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\{SubscriptionRequest};
use App\Repositories\Api\{SubscriptionRepository};
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Helpers\ExceptionHandlerHelper;

class SubscriptionController extends Controller
{
    use ResponseTrait;
    protected $repository;

    public function __construct(SubscriptionRepository $repository)
    {
        $this->repository = $repository;
    }



    public function store(SubscriptionRequest $request)
    {
        return ExceptionHandlerHelper::tryCatch(function()use($request){
            $data = $request->validated();
            $this->repository->create($data);
            return $this->sendResponse($data,'Subscription created successfully');
        });

    }


}