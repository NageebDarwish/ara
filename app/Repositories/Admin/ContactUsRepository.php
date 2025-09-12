<?php

namespace App\Repositories\Admin;

use App\Models\ContactUs;


class ContactUsRepository
{
    private $model;

    public function __construct(ContactUs $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function delete($id)
    {
        $model = $this->model->findOrFail($id);
        return $model->delete();
    }
}
