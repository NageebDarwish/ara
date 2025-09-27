<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Admin\UserRepository;
use App\Models\BadgeModal;

class UserController extends Controller
{
    protected $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $users = $this->repository->index($perPage);

        return view('admin.modules.users.index', compact('users'));
    }

    public function delete($id)
    {
        $user = $this->repository->findOrFail($id);
        if ($user) {

            $modals = BadgeModal::where('user_id', $user->id)->delete();

            $user->delete();
            return redirect()->back()->with('success', 'User deleted successfully');
        }
        return redirect()->back()->with('error', 'User not found');
    }
}