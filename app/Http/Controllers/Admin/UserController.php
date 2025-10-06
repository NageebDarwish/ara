<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Admin\UserRepository;
use App\Models\BadgeModal;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

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
        $perPage = (int) $request->get('per_page', 10);
        $users = $this->repository->index($perPage);

        return view('admin.modules.users.index', compact('users'));
    }

    /**
     * Get users data for DataTables AJAX
     */
    public function getUsersData(Request $request)
    {
        $tab = $request->get('tab', 'users');

        if ($tab === 'users') {
            $users = $this->repository->getUsersForDataTable();

            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('is_premium', function ($user) {
                    return $user->is_premium ? 'Yes' : 'No';
                })
                ->addColumn('progress_level', function ($user) {
                    return $user->progressLevel ? $user->progressLevel->name : '-';
                })
                ->addColumn('total_watching_hours', function ($user) {
                    $totalSeconds = $user->total_watching_hours;
                    $hours = floor($totalSeconds / 3600);
                    $minutes = floor(($totalSeconds % 3600) / 60);
                    $seconds = $totalSeconds % 60;
                    return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
                })
                ->addColumn('actions', function ($user) {
                    $actions = '';
                    if (Auth::user() && Auth::user()->role == 'admin') {
                        $actions .= '<form method="POST" action="' . route('admin.users.togglePremium', $user->id) . '" style="display:inline-block;" class="me-2 align-middle premium-toggle-form">
                            ' . csrf_field() . '
                            <div class="custom-control custom-switch m-0">
                                <input type="checkbox" class="custom-control-input permission-toggle" role="switch" id="premium_switch_' . $user->id . '" ' . ($user->is_premium ? 'checked' : '') . ' onchange="this.form.submit()" title="Toggle Premium">
                                <label class="custom-control-label" for="premium_switch_' . $user->id . '"></label>
                            </div>
                        </form>';
                        $actions .= '<a href="' . route('admin.users.edit', $user->id) . '" class="btn btn-warning btn-sm me-2" title="Edit User"><i class="fa fa-edit"></i></a>';
                        $actions .= '<a href="' . route('admin.users.password', $user->id) . '" class="btn btn-secondary btn-sm mx-2" title="Change Password"><i class="fa fa-key"></i></a>';
                        $actions .= '<form method="POST" action="' . route('admin.users.delete', $user->id) . '" style="display:inline-block;" title="Delete">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <button type="submit" class="btn btn-danger btn-sm delete-btn"><i class="fa fa-trash"></i></button>
                        </form>';
                    }
                    return $actions;
                })
                ->rawColumns(['actions'])
                ->make(true);
        } elseif ($tab === 'managers') {
            $managers = $this->repository->getManagersForDataTable();

            return DataTables::of($managers)
                ->addIndexColumn()
                ->addColumn('actions', function ($manager) {
                    $actions = '<a href="' . route('admin.manager.edit', $manager->id) . '" class="btn btn-warning btn-sm me-2" title="Edit Manager"><i class="fa fa-edit"></i></a>';
                    $actions .= '<form method="POST" action="' . route('admin.manager.destroy', $manager->id) . '" style="display:inline;" title="Delete Manager">
                        ' . csrf_field() . '
                        ' . method_field('DELETE') . '
                        <button type="submit" class="btn btn-danger btn-sm delete-btn"><i class="fa fa-trash"></i></button>
                    </form>';
                    return $actions;
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return response()->json(['error' => 'Invalid tab'], 400);
    }

    public function togglePremium($id)
    {
        $user = User::findOrFail($id);
        $user->is_premium = $user->is_premium ? 0 : 1;
        $user->save();
        return redirect()->back()->with('success', 'User premium status updated');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        session(['user_return_url' => url()->previous()]);
        return view('admin.modules.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);
        $user->update($validated);
        $returnUrl = session('user_return_url', route('admin.users.index'));
        session()->forget('user_return_url');
        return redirect($returnUrl)->with('success', 'User updated successfully');
    }

    public function editPassword($id)
    {
        $user = User::findOrFail($id);
        // Store the previous URL to return to the same pagination page
        session(['user_return_url' => url()->previous()]);
        return view('admin.modules.users.password', compact('user'));
    }

    public function updatePassword(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $validated = $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);
        $user->password = Hash::make($validated['password']);
        $user->save();
        // Return to the stored URL or default to users index
        $returnUrl = session('user_return_url', route('admin.users.index'));
        session()->forget('user_return_url');
        return redirect($returnUrl)->with('success', 'Password updated successfully');
    }

    public function delete($id)
    {
        $user = $this->repository->findOrFail($id);
        if ($user) {
            $user->delete();
            return redirect()->back()->with('success', 'User deleted successfully');
        }
        return redirect()->back()->with('error', 'User not found');
    }
}
