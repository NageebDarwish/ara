<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class BlogRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'cover_image'=>'required|image|mimes:jpeg,png,jpg,gif,svg',
            'title'=>'required|string|max:255',
            'meta_title'=>'nullable|string|max:255',
            'description'=>'required',
            'meta_description'=>'nullable',
            'content'=>'required',
            'slug' => 'nullable|string|max:255|unique:blogs,slug|regex:/^[A-Za-z0-9_-]+$/',
            'author'=>'required|string|max:255',
             'blog_category_id'=>'required',
        ];
    }
}
