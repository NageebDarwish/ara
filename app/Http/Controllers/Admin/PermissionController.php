<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class PermissionController extends Controller
{
    public function toggle(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'permission' => 'required|exists:permissions,name',
        ]);

        $user = User::findOrFail($request->user_id);
        $permission = $request->permission;

        if ($user->hasPermissionTo($permission)) {
            $user->revokePermissionTo($permission);
            $message = 'Permission revoked successfully';
        } else {
            $user->givePermissionTo($permission);
            $message = 'Permission granted successfully';
        }

        return redirect()->back()->with('success', $message);
    }
}