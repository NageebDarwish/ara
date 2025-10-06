<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\{BlogRequest};
use App\Repositories\Admin\{BlogRepository};
use Illuminate\Http\Request;
use App\Models\BlogCategory;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    protected $repository;

    public function __construct(BlogRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        return view('admin.modules.blog.index');
    }

    public function getBlogsData(Request $request)
    {
        $blogs = $this->repository->getBlogsForDataTable();

        return DataTables::of($blogs)
            ->addIndexColumn()
            ->addColumn('category_name', function ($blog) {
                return $blog->category->name ?? 'N/A';
            })
            ->addColumn('title_with_image', function ($blog) {
                return '<div class="d-flex px-2 py-1">
                    <div>
                        <img src="' . asset($blog->cover_image) . '" class="avatar avatar-sm me-3 border-radius-lg" alt="' . $blog->title . '">
                    </div>
                    <div class="d-flex flex-column justify-content-center mx-2">
                        <h6 class="mb-0 text-sm">' . $blog->title . '</h6>
                        <p class="text-xs text-secondary mb-0">' . Str::limit($blog->meta_title, 30) . '</p>
                    </div>
                </div>';
            })
            ->addColumn('created', function ($blog) {
                return $blog->created_at->format('d M Y');
            })
            ->addColumn('actions', function ($blog) {
                $actions = '<div class="d-flex align-items-center" style="gap: 0.5rem;">';
                $actions .= '<a href="' . route('admin.blog.edit', $blog->id) . '" class="btn btn-warning btn-sm" title="Edit Blog"><i class="fa fa-edit"></i></a>';
                $actions .= '<form action="' . route('admin.blog.destroy', $blog->id) . '" method="POST" style="display:inline-block;">
                    ' . csrf_field() . '
                    ' . method_field('DELETE') . '
                    <button type="submit" class="btn btn-danger btn-sm delete-btn" title="Delete Blog"><i class="fa fa-trash"></i></button>
                </form>';
                $actions .= '</div>';
                return $actions;
            })
            ->rawColumns(['title_with_image', 'actions'])
            ->make(true);
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
