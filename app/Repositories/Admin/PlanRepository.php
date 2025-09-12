<?php

namespace App\Repositories\Admin;

use App\Models\Plan;
use Illuminate\Support\Facades\Storage;
use App\Helpers\UploadFiles;

class PlanRepository
{
    private $model;

    public function __construct(Plan $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        if (isset($data['image'])) {
            $imagePath = UploadFiles::upload($data['image'], 'plan');
            $data['image'] = $imagePath;
        }

        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $model = $this->model->findOrFail($id);
        if (isset($data['image'])) {
            if ($model->image) {
                Storage::delete('public/plan/'.basename($model->image));
            }
            $data['image'] = UploadFiles::upload($data['image'], 'plan');
        }
        $model->update($data);

        return $model;
    }

    public function delete($id)
    {
        $model = $this->model->findOrFail($id);
        if ($model->image) {
            Storage::delete('public/plan/'.basename($model->image));
        }

        return $model->delete();
    }
}
