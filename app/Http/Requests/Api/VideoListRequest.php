<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class VideoListRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'video_id'=>'required|exists:videos,id',
        ];
    }
}