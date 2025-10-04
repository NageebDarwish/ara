<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Admin\UserRepository;
use App\Models\BadgeModal;
use Yajra\DataTables\Facades\DataTables;

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
                    if (auth()->user()->role == 'admin') {
                        $actions .= '<form method="POST" action="' . route('admin.users.delete', $user->id) . '" style="display:inline;" onsubmit="return confirm(\'Are you sure you want to delete this user?\')">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
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
                    $actions = '<a href="' . route('admin.manager.edit', $manager->id) . '" class="btn btn-warning btn-sm me-2">
                        <i class="fas fa-edit"></i>
                    </a>';
                    $actions .= '<form method="POST" action="' . route('admin.manager.destroy', $manager->id) . '" style="display:inline;" onsubmit="return confirm(\'Are you sure you want to delete this manager?\')">
                        ' . csrf_field() . '
                        ' . method_field('DELETE') . '
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>';
                    return $actions;
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        
        return response()->json(['error' => 'Invalid tab'], 400);
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