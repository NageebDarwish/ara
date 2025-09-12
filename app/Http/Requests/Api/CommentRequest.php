<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'type'=>'required|in:video,series,',
            'video_id'=>'required',
            'comment'=>'required',
        ];
    }
}