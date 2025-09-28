<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Repositories\Admin\CategoryRepository;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $repository;

    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $data = $this->repository->all();

        return view('admin.modules.category.index', compact('data'));
    }

    public function create()
    {
        return view('admin.modules.category.create');
    }

    public function store(CategoryRequest $request)
    {
        $data = $request->validated();
        $this->repository->create($data);

        return redirect()->route('admin.category.index')->with('success', 'Category created successfully.');
    }

    public function show($id)
    {
        $category = $this->repository->find($id);

        return view('admin.modules.category.show', compact('category'));
    }

    public function edit($id)
    {
        $category = $this->repository->find($id);

        return view('admin.modules.category.edit', compact('category'));
    }

    public function update(CategoryRequest $request, $id)
    {
        $data = $request->validated();
        $this->repository->update($id, $data);

        return redirect()->route('admin.category.index')->with('success', 'Category updated successfully.');
    }

    public function destroy($id)
    {
        $this->repository->delete($id);

        return redirect()->route('admin.category.index')->with('success', 'Category deleted successfully.');
    }
}