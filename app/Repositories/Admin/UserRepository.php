<?php

namespace App\Repositories\Admin;

use App\Models\User;


class UserRepository
{
    private $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function index()
    {
       return $this->model::whereIn('role', ['user','manager'])->get();
    }

    public function findOrFail($id)
    {
        return $this->model::find($id);
    }
}