<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\GuideRequest;
use App\Repositories\Admin\GuideRepository;
use Yajra\DataTables\Facades\DataTables;

class GuideController extends Controller
{
    protected $repository;

    public function __construct(GuideRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        return view('admin.modules.guide.index');
    }

    public function getGuidesData(Request $request)
    {
        $guides = $this->repository->getGuidesForDataTable();

        return DataTables::of($guides)
            ->addIndexColumn()
            ->addColumn('actions', function ($guide) {
                $actions = '<a href="' . route('admin.guides.edit', $guide->id) . '" class="btn btn-warning btn-sm me-2" title="Edit Guide"><i class="fa fa-edit"></i></a>';
                $actions .= '<form method="POST" action="' . route('admin.guides.destroy', $guide->id) . '" style="display:inline;" title="Delete Guide">
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
        return view('admin.modules.guide.create');
    }

    public function store(GuideRequest $request)
    {
        $data = $request->validated();
        $this->repository->create($data);

        return redirect()->route('admin.guides.index')->with('success', 'Created successfully.');
    }

    public function edit($id)
    {
        $guide = $this->repository->find($id);

        return view('admin.modules.guide.edit', compact('guide'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $this->repository->update($id, $data);

        return redirect()->route('admin.guides.index')->with('success', 'Updated successfully.');
    }

    public function destroy($id)
    {
        $this->repository->delete($id);

        return redirect()->route('admin.guides.index')->with('success', 'Deleted successfully.');
    }
}
