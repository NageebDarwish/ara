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

    public function all($filter = null)
    {
        $query = $this->model->query();

        // Apply filtering based on read status
        switch ($filter) {
            case 'read':
                $query->whereNotNull('read_at');
                break;
            case 'unread':
                $query->whereNull('read_at');
                break;
            case 'all':
            default:
                // No filter, show all messages
                break;
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function delete($id)
    {
        $model = $this->model->findOrFail($id);
        return $model->delete();
    }

    public function markAsRead($id)
    {
        $model = $this->model->findOrFail($id);
        $model->read_at = now();
        return $model->save();
    }
}
