<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\{BlogRequest};
use App\Repositories\Admin\{BlogRepository};
use Illuminate\Http\Request;
use App\Models\BlogCategory;

class BlogController extends Controller
{
    protected $repository;

    public function __construct(BlogRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $data = $this->repository->all();

        return view('admin.modules.blog.index', compact('data'));
    }

    public function create()
    {
         $categories=BlogCategory::all();
        return view('admin.modules.blog.create',compact('categories'));
    }

    public function store(BlogRequest $request)
    {
        $data = $request->validated();
        $this->repository->create($data);

        return redirect()->route('admin.blog.index')->with('success', 'Created successfully.');
    }

    public function edit($id)
    {
         $categories=BlogCategory::all();
        $blog = $this->repository->find($id);

        return view('admin.modules.blog.edit', compact('blog','categories'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $this->repository->update($id, $data);

        return redirect()->route('admin.blog.index')->with('success', 'Updated successfully.');
    }

    public function destroy($id)
    {
        $this->repository->delete($id);

        return redirect()->route('admin.blog.index')->with('success', 'Deleted successfully.');
    }
}
