<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ContactUsRequest extends FormRequest
{
   
    public function rules(): array
    {
        return [
            'name'=>'required|string|max:255',
            'email'=>'required|email',
            'subject'=>'required|string|max:255',
            'message'=>'required',
        ];
    }
}
