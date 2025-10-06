<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\LevelRequest;
use App\Repositories\Admin\LevelRepository;
use Yajra\DataTables\Facades\DataTables;

class LevelController extends Controller
{
    protected $repository;

    public function __construct(LevelRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        return view('admin.modules.level.index');
    }

    public function getLevelsData(Request $request)
    {
        $levels = $this->repository->getLevelsForDataTable();

        return DataTables::of($levels)
            ->addIndexColumn()
            ->addColumn('actions', function ($level) {
                $actions = '<a href="' . route('admin.levels.edit', $level->id) . '" class="btn btn-warning btn-sm me-2" title="Edit Level"><i class="fa fa-edit"></i></a>';
                $actions .= '<form method="POST" action="' . route('admin.levels.destroy', $level->id) . '" style="display:inline;" title="Delete Level">
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
