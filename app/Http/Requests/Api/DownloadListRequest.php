<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Video;
use App\Models\SeriesVideo;

class DownloadListRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'video_id' => [
                'required',
                'integer',
                function($attribute, $value, $fail) {
                    if ($this->input('type') === 'video') {
                        if (!Video::where('id', $value)->exists()) {
                            $fail('The selected video is invalid.');
                        }
                    } elseif ($this->input('type') === 'series_video') {
                        if (!SeriesVideo::where('id', $value)->exists()) {
                            $fail('The selected series video is invalid.');
                        }
                    } else {
                        $fail('Invalid video type.');
                    }
                }
            ],
            'type' => 'required|string|in:video,series_video',
        ];
    }

    public function messages(): array
    {
        return [
            'video_id.required' => 'The video id is required.',
            'type.required' => 'The type is required.',
            'type.in' => 'The type must be either video or series video.',
        ];
    }
}