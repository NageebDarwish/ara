<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\{ContactUsRequest};
use App\Repositories\Api\{ContactUsRepository};
use Illuminate\Http\Request;
use App\Helpers\ExceptionHandlerHelper;
use App\Traits\ResponseTrait;

class ContactUsController extends Controller
{
    protected $repository;
     use ResponseTrait;

    public function __construct(ContactUsRepository $repository)
    {
        $this->repository = $repository;
    }


    public function store(ContactUsRequest $request)
    {
        return ExceptionHandlerHelper::tryCatch(function() use($request){
            $data = $request->validated();
            $this->repository->create($data);
            return $this->sendResponse($data,'Message sent to admin successfully');
        });
       
    }

}
