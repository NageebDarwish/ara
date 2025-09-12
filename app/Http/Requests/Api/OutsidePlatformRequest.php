<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class OutsidePlatformRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'date' => 'required',
            'activity' => 'required',
            'duration'  => 'required',
            'description' => 'nullable'
        ];
    }
}
