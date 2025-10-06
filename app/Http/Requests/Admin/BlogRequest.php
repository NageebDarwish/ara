<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class BlogRequest extends FormRequest
{

    public function rules(): array
    {
        $rules = [
            'cover_image'=>'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'title'=>'required|string|max:255',
            'meta_title'=>'nullable|string|max:255',
            'description'=>'required',
            'meta_description'=>'nullable',
            'content'=>'required',
            'slug' => 'nullable|string|max:255|regex:/^[A-Za-z0-9_-]+$/',
            'author'=>'required|string|max:255',
            'blog_category_id'=>'required',
            'status'=>'required|in:draft,scheduled,published',
            'published_at'=>'nullable|date',
        ];

        // Make cover_image required only on create
        if ($this->isMethod('post')) {
            $rules['cover_image'] = 'required|image|mimes:jpeg,png,jpg,gif,svg';
        }

        // Make slug unique except for current blog on update
        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $blogId = $this->route('blog');
            $rules['slug'] = 'nullable|string|max:255|unique:blogs,slug,' . $blogId . '|regex:/^[A-Za-z0-9_-]+$/';
        } else {
            $rules['slug'] = 'nullable|string|max:255|unique:blogs,slug|regex:/^[A-Za-z0-9_-]+$/';
        }

        return $rules;
    }
}
