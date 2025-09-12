<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CountryRequest;
use App\Repositories\Admin\CountryRepository;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    protected $repository;

    public function __construct(CountryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $data = $this->repository->all();

        return view('admin.modules.country.index', compact('data'));
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