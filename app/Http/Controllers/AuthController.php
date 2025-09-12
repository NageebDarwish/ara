<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\{User, Video, Series, Topic, Guide, Level,SeriesVideo};

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.modules.authentication.index');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->intended('/admin/dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('login');
    }

    public function dashboard()
    {
        $count['user'] = User::where('role', 'user')->count();
        $count['series'] = Series::count();
        $count['videos'] = Video::count();
        $count['series_videos'] = SeriesVideo::count();
        return view('admin.modules.dashboard', compact('count'));
    }
}
