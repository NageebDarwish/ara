<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\LevelRequest;
use App\Repositories\Admin\LevelRepository;

class LevelController extends Controller
{
    protected $repository;

    public function __construct(LevelRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $data = $this->repository->all();

        return view('admin.modules.level.index', compact('data'));
    }

    public function create()
    {
        return view('admin.modules.level.create');
    }

    public function store(LevelRequest $request)
    {
        $data = $request->validated();
        $this->repository->create($data);

        return redirect()->route('admin.levels.index')->with('success', 'Created successfully.');
    }

    public function edit($id)
    {
        $level = $this->repository->find($id);

        return view('admin.modules.level.edit', compact('level'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $this->repository->update($id, $data);

        return redirect()->route('admin.levels.index')->with('success', 'Updated successfully.');
    }

    public function destroy($id)
    {
        $this->repository->delete($id);

        return redirect()->route('admin.levels.index')->with('success', 'Deleted successfully.');
    }
}