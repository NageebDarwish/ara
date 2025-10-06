<?php

namespace App\Repositories\Admin;

use App\Models\Level;


class LevelRepository
{
    private $model;

    public function __construct(Level $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function getLevelsForDataTable()
    {
        return $this->model->select(['id', 'name'])->orderBy('created_at', 'desc');
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $model = $this->model->findOrFail($id);
        $model->update($data);

        return $model;
    }

    public function delete($id)
    {
        $model = $this->model->findOrFail($id);
        return $model->delete();
    }
}
