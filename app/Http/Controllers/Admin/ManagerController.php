<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Admin\ManagerRepository;

class ManagerController extends Controller
{
    protected $repository;

    public function __construct(ManagerRepository $repository)
    {
        $this->repository=$repository;
    }
    /**
     * Display a listing of the resource.
     */


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.modules.manager.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'email'=>'required|email|unique:users,email',
        ]);

        $this->repository->store($request->all());
        return redirect()->route('admin.users.index')->with('success','Manager created successfully');
    }

    /**
     * Display the specified resource.
     */


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $manager=$this->repository->find($id);
        return view('admin.modules.manager.edit',compact('manager'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->repository->update($request->all(),$id);
        return redirect()->route('admin.users.index')->with('success','Manager updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->repository->delete($id);
        return redirect()->back()->with('success','Manager deleted successfully');
    }
}