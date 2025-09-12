<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class VideoRequest extends FormRequest
{

    public function rules(): array
    {
        return [
        'topic_id'=>'required|exists:topics,id',
        'guide_id'=>'required|exists:guides,id',
        'level_id'=>'required|exists:levels,id',
        'title'=>'required|max:225',
        'description'=>'required',
        'video'=>'required',
        ];
    }
}