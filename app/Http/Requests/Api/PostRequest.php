<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'subject'=>'required',
            'body'=>'required',
            'file'=>'nullable',
            'tags'=>'nullable|array',
        ];
    }
}
