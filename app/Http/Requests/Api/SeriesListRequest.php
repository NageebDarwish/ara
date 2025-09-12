<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class SeriesListRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'series_id'=>'required|exists:series,id',
        ];
    }
}
