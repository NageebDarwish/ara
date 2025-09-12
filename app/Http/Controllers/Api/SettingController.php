<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Traits\ResponseTrait;
use App\Helpers\ExceptionHandlerHelper;

class SettingController extends Controller
{
    use ResponseTrait;
    public function index()
    {
        return ExceptionHandlerHelper::tryCatch(function(){
            $setting=Setting::first();
            return $this->sendResponse( $setting,'all settings');
        });

    }

}
