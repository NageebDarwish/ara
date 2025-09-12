<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class SeriesVideoListRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'series_video_id'=>'required|exists:series_videos,id',
        ];
    }
}
