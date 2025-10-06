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

    public function index($perPage = null)
    {
        $query = $this->model::whereIn('role', ['user', 'manager']);
        return $perPage ? $query->paginate($perPage) : $query->get();
    }

    public function findOrFail($id)
    {
        return $this->model::find($id);
    }

    public function getUsersForDataTable()
    {
        return $this->model::where('role', 'user')
            ->with(['progressLevel'])
            ->select(['id', 'name', 'email', 'is_premium', 'total_watching_hours', 'progress_level_id']);
    }

    public function getManagersForDataTable()
    {
        return $this->model::where('role', 'manager')
            ->select(['id', 'name', 'email']);
    }
}
