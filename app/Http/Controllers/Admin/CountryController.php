<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CountryRequest;
use App\Repositories\Admin\CountryRepository;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CountryController extends Controller
{
    protected $repository;

    public function __construct(CountryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        return view('admin.modules.country.index');
    }

    public function getCountriesData(Request $request)
    {
        $countries = $this->repository->getCountriesForDataTable();

        return DataTables::of($countries)
            ->addIndexColumn()
            ->addColumn('actions', function ($country) {
                $actions = '<a href="' . route('admin.country.edit', $country->id) . '" class="btn btn-warning btn-sm me-2" title="Edit Country"><i class="fa fa-edit"></i></a>';
                $actions .= '<form method="POST" action="' . route('admin.country.destroy', $country->id) . '" style="display:inline;" title="Delete Country">
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
        return view('admin.modules.country.create');
    }

    public function store(CountryRequest $request)
    {
        $data = $request->validated();
        $this->repository->create($data);

        return redirect()->route('admin.country.index')->with('success', 'Created successfully.');
    }

    public function edit($id)
    {
        $country = $this->repository->find($id);

        return view('admin.modules.country.edit', compact('country'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $this->repository->update($id, $data);

        return redirect()->route('admin.country.index')->with('success', 'Updated successfully.');
    }

    public function destroy($id)
    {
        $this->repository->delete($id);

        return redirect()->route('admin.country.index')->with('success', 'Deleted successfully.');
    }
}
