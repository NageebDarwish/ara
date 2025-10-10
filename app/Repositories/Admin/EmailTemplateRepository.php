<?php

namespace App\Repositories\Admin;

use App\Models\EmailTemplate;

class EmailTemplateRepository
{
    private $model;

    public function __construct(EmailTemplate $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->orderBy('created_at', 'desc')->get();
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        if (isset($data['variables']) && is_array($data['variables'])) {
            $data['variables'] = json_encode($data['variables']);
        }

        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $template = $this->model->findOrFail($id);

        if (isset($data['variables']) && is_array($data['variables'])) {
            $data['variables'] = json_encode($data['variables']);
        }

        $template->update($data);
        return $template;
    }

    public function delete($id)
    {
        $template = $this->model->findOrFail($id);
        return $template->delete();
    }

    public function getForDataTable()
    {
        return $this->model->select(['id', 'name', 'subject', 'trigger_event', 'is_active', 'created_at'])
            ->orderBy('created_at', 'desc');
    }
}

