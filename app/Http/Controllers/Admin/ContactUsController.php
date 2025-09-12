<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\{ContactUsRepository};
use Illuminate\Http\Request;

class ContactUsController extends Controller
{
    protected $repository;

    public function __construct(ContactUsRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $data = $this->repository->all();

        return view('admin.modules.contactus.index', compact('data'));
    }

    public function destroy($id)
    {
        $this->repository->delete($id);

        return redirect()->route('admin.contactus.index')->with('success', 'Deleted successfully.');
    }
}
