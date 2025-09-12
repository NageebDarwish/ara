<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\{PlanRequest};
use App\Repositories\Admin\{PlanRepository};
use Illuminate\Http\Request;

class PlanController extends Controller
{
    protected $repository;

    public function __construct(PlanRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $data = $this->repository->all();

        return view('admin.modules.plan.index', compact('data'));
    }

    public function create()
    {
        return view('admin.modules.plan.create');
    }

    public function store(PlanRequest $request)
    {
        $data = $request->validated();
        $this->repository->create($data);

        return redirect()->route('admin.plan.index')->with('success', 'Created successfully.');
    }

    public function edit($id)
    {
        $data = $this->repository->find($id);

        return view('admin.modules.plan.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $this->repository->update($id, $data);

        return redirect()->route('admin.plan.index')->with('success', 'Updated successfully.');
    }

    public function destroy($id)
    {
        $this->repository->delete($id);

        return redirect()->route('admin.plan.index')->with('success', 'Deleted successfully.');
    }
}
