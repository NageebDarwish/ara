<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class LevelRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'=>'required|max:225',
        ];
    }
}
