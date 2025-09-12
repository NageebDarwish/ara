<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ExceptionHandlerHelper;
use App\Traits\ResponseTrait;
use App\Models\Page;

class PageController extends Controller
{

    use ResponseTrait;

    public function index($name)
    {
        return ExceptionHandlerHelper::tryCatch(function() use($name){
            $data =Page::where('name',$name)->first();
            if(!$data){
                return $this->sendError('Page not found',404);
            }else
            {
                return $this->sendResponse($data,'Pages details');
            }

        });
    }



}
