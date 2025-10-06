<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Repositories\Admin\CategoryRepository;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    protected $repository;

    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        return view('admin.modules.category.index');
    }

    public function getCategoriesData(Request $request)
    {
        $categories = $this->repository->getCategoriesForDataTable();

        return DataTables::of($categories)
            ->addIndexColumn()
            ->addColumn('actions', function ($category) {
                $actions = '<a href="' . route('admin.category.edit', $category->id) . '" class="btn btn-warning btn-sm me-2" title="Edit Category"><i class="fa fa-edit"></i></a>';
                $actions .= '<form method="POST" action="' . route('admin.category.destroy', $category->id) . '" style="display:inline;" title="Delete Category">
                    ' . csrf_field() . '
                    ' . method_field('DELETE') . '
                    <button type="submit" class="btn btn-danger btn-sm delete-btn"><i class="fa fa-trash"></i></button>
                </form>';
                return $actions;
            })
            ->rawColumns(['actions'])
            ->make(true);
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
