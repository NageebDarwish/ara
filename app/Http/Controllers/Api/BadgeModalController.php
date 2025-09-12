<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ExceptionHandlerHelper;
use App\Traits\ResponseTrait;
use Auth;
use App\Models\BadgeModal;

class BadgeModalController extends Controller
{
    use ResponseTrait;

    public function store($id)
    {
        return ExceptionHandlerHelper::tryCatch(function() use($id){
            BadgeModal::updateOrCreate(
                [
                'user_id'=>auth()->id(),
                'badge_id'=>$id,
                ],
                [
                'opened'=>true,
            ]);
            return $this->sendResponse('','badge modal created successfully');
        });

    }
}