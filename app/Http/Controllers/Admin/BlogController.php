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
            ->addColumn('status_badge', function ($blog) {
                $badges = [
                    'draft' => '<span class="badge badge-secondary">Draft</span>',
                    'scheduled' => '<span class="badge badge-warning">Scheduled</span>',
                    'published' => '<span class="badge badge-success">Published</span>',
                ];
                return $badges[$blog->status] ?? '<span class="badge badge-secondary">' . ucfirst($blog->status) . '</span>';
            })
            ->addColumn('publish_date', function ($blog) {
                if ($blog->published_at) {
                    // Return ISO format with data attribute for JS to convert to local time
                    return '<span class="local-time" data-utc="' . $blog->published_at->toIso8601String() . '">' . $blog->published_at->format('d M Y H:i') . ' UTC</span>';
                }
                return 'N/A';
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
            ->rawColumns(['title_with_image', 'status_badge', 'publish_date', 'actions'])
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

        if ($data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        } elseif ($data['status'] === 'scheduled' && !empty($data['published_at'])) {
            // Parse as local time, then convert to UTC for storage
            $data['published_at'] = \Carbon\Carbon::parse($data['published_at'], $request->input('timezone', 'UTC'))->utc();
        } elseif ($data['status'] === 'draft') {
            $data['published_at'] = null;
        }

        $this->repository->create($data);

        return redirect()->route('admin.blog.index')->with('success', 'Blog post created successfully.');
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

        // Handle publish logic based on status
        if ($data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        } elseif ($data['status'] === 'scheduled' && !empty($data['published_at'])) {
            // Parse as local time, then convert to UTC for storage
            $data['published_at'] = \Carbon\Carbon::parse($data['published_at'], $request->input('timezone', 'UTC'))->utc();
        } elseif ($data['status'] === 'draft') {
            // Drafts don't need a publish date
            $data['published_at'] = null;
        }

        $this->repository->update($id, $data);

        return redirect()->route('admin.blog.index')->with('success', 'Blog post updated successfully.');
    }

    public function destroy($id)
    {
        $this->repository->delete($id);

        return redirect()->route('admin.blog.index')->with('success', 'Deleted successfully.');
    }
}
