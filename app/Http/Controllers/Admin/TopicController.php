<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TopicRequest;
use App\Repositories\Admin\TopicRepository;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    protected $repository;

    public function __construct(TopicRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $data = $this->repository->all();

        return view('admin.modules.topic.index', compact('data'));
    }

    public function create()
    {
        return view('admin.modules.topic.create');
    }

    public function store(TopicRequest $request)
    {
        $data = $request->validated();
        $this->repository->create($data);

        return redirect()->route('admin.topic.index')->with('success', 'Created successfully.');
    }

    public function edit($id)
    {
        $topic = $this->repository->find($id);

        return view('admin.modules.topic.edit', compact('topic'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $this->repository->update($id, $data);

        return redirect()->route('admin.topic.index')->with('success', 'Updated successfully.');
    }

    public function destroy($id)
    {
        $this->repository->delete($id);

        return redirect()->route('admin.topic.index')->with('success', 'Deleted successfully.');
    }
}