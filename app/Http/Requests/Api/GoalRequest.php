<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class GoalRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'date'=>'required|date',
            'target_minutes'=>'required|integer',
            'completed_minutes'=>'nullable',
        ];
    }
}