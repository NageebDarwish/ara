<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TopicRequest;
use App\Repositories\Admin\TopicRepository;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TopicController extends Controller
{
    protected $repository;

    public function __construct(TopicRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        return view('admin.modules.topic.index');
    }

    public function getTopicsData(Request $request)
    {
        $topics = $this->repository->getTopicsForDataTable();

        return DataTables::of($topics)
            ->addIndexColumn()
            ->addColumn('actions', function ($topic) {
                $actions = '<a href="' . route('admin.topic.edit', $topic->id) . '" class="btn btn-warning btn-sm me-2" title="Edit Topic"><i class="fa fa-edit"></i></a>';
                $actions .= '<form method="POST" action="' . route('admin.topic.destroy', $topic->id) . '" style="display:inline;" title="Delete Topic">
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
