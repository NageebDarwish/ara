<?php

namespace App\Repositories\Admin;

use App\Models\Blog;
use App\Helpers\UploadFiles;

class BlogRepository
{
    private $model;

    public function __construct(Blog $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->orderBy('created_at','desc')->get();
    }

    public function getBlogsForDataTable()
    {
        return $this->model->with(['category'])
            ->select(['id', 'blog_category_id', 'author', 'title', 'meta_title', 'slug', 'cover_image', 'status', 'published_at', 'created_at'])
            ->orderBy('created_at', 'desc');
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        if (isset($data['cover_image'])) {
            $imagePath = UploadFiles::upload($data['cover_image'], 'blog');
            $data['cover_image'] = $imagePath;
        }

        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $model = $this->model->findOrFail($id);
        if (isset($data['cover_image'])) {
            if ($model->cover_image) {
                UploadFiles::delete($model->cover_image,'blog');
            }
            $data['cover_image'] = UploadFiles::upload($data['cover_image'], 'blog');
        }
        $model->update($data);

        return $model;
    }

    public function delete($id)
    {
        $model = $this->model->findOrFail($id);
        if ($model->cover_image) {
            UploadFiles::delete($model->cover_image,'blog');
        }

        return $model->delete();
    }
}
