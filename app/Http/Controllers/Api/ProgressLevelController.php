<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProgressLevel;
use App\Traits\ResponseTrait;
use App\Helpers\ExceptionHandlerHelper;

class ProgressLevelController extends Controller
{
    public function index()
    {
        return ExceptionHandlerHelper::tryCatch(function(){
            $data = ProgressLevel::all();
            return $this->sendResponse($data,'All Levels');
        });
    }
}
