<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\PageRepository;
use Illuminate\Http\Request;

class PageController extends Controller
{

protected $repository;

    public function __construct(PageRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $data = $this->repository->all();

        return view('admin.modules.page.index', compact('data'));
    }


    public function edit($id)
    {
        $page = $this->repository->find($id);

        return view('admin.modules.page.edit', compact('page'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'slug' => [
                'string',
                'max:255',
                'regex:/^[a-z0-9-]+$/',
                'unique:pages,slug,'.$id
            ],
        ], [
            'slug.regex' => 'The slug may only contain lowercase letters, numbers, and hyphens without spaces.',
            'slug.unique' => 'This slug is already in use.'
        ]);
        $data = $request->all();
        $this->repository->update($id, $data);

        return redirect()->route('admin.page.index')->with('success', 'Updated successfully.');
    }

}
