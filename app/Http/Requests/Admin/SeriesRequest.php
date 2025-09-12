<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SeriesRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'level_id' => 'required|exists:levels,id',
            'topic_id' => 'required|exists:topics,id',
            'guide_id' => 'required|exists:guides,id',
            'title' => 'required|max:225',
            'description' => 'required',
            'video_title.*' => 'required|max:255', 
            'video_description.*' => 'nullable', 
            'plan.*' => 'nullable', 
            'videos.*' => 'required|nullable|file|mimes:mp4,avi,mov,wmv', 
        ];
    }

    public function messages()
    {
        return [
            'level_id.required' => 'The level field is required.',
            'level_id.exists' => 'The selected level does not exist.',
            'topic_id.required' => 'The topic field is required.',
            'topic_id.exists' => 'The selected topic does not exist.',
            'guide_id.required' => 'The topic guide is required.',
            'guide_id.exists' => 'The selected guide does not exist.',
            'title.required' => 'The title field is required.',
            'title.max' => 'The title may not be greater than 225 characters.',
            'description.required' => 'The description field is required.',
            'video_title.*.required' => 'Each video title is required.',
            'video_title.*.max' => 'Each video title may not be greater than 255 characters.',
            'videos.*.file' => 'Each video must be a valid file.',
            'videos.*.mimes' => 'Each video must be of type: mp4, avi, mov, wmv.'
        ];
    }
}