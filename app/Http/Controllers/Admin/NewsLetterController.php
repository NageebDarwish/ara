<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\NewsLetter;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class NewsLetterController extends Controller
{

    public function index()
    {
        return view('admin.modules.newsletter.index');
    }
    public function sendNewsLetter(Request $request)
    {
        $request->validate([
            'subject'=>'required',
            'body'=>"required",
        ]);
        $users=User::where('role', 'user')->get();
      
        foreach($users as $user)
        {
            Mail::to($user->email)->send(new NewsLetter($request->subject,$request->body));
        }

        return redirect()->back()->with('success','Sent successfully');
    }
}
