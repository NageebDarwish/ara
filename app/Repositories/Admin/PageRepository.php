<?php

namespace App\Repositories\Admin;

use App\Models\Page;


class PageRepository
{
    private $model;

    public function __construct(Page $model)
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



    public function update($id, array $data)
    {
        $model = $this->model->findOrFail($id);
        $model->update($data);

        return $model;
    }


}
