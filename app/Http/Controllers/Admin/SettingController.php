<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        $setting=Setting::first();
        return view('admin.modules.setting.index',compact('setting'));
    }
    public function edit()
    {
        $setting=Setting::first();
        return view('admin.modules.setting.edit',compact('setting'));
    }

    public function update(Request $request,$id)
    {
        $setting=Setting::findOrfail($id);
        $setting->update($request->all());
        return redirect()->route('admin.setting.index')->with('success','Data added successfully');
    }
}
