<?php

namespace App\Repositories\Api;

use App\Models\ContactUs;


class ContactUsRepository
{
    private $model;

    public function __construct(ContactUs $model)
    {
        $this->model = $model;
    }


    public function create(array $data)
    {
        return $this->model->create($data);
    }

}
