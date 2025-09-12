<?php

namespace App\Repositories\Admin;

use App\Models\User;
use Hash;

class ManagerRepository
{
    private $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }
    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function store($data)
    {
        $data['password']=Hash::make($data['password']);
        $data['role']='manager';
        $user=$this->model->create($data);
        return $user;
    }

    public function update($data,$id)
    {
        $user=$this->model->findOrFail($id);
        if(isset($data['password']))
        {
            $data['password']=Hash::make($data['password']);
        }
        $user->update($data);
        return $user;

    }

    public function delete($id)
    {
        $user=$this->model->findOrFail($id);
        return $user->delete();
    }
}