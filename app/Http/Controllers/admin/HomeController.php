<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
class HomeController extends Controller
{
    public function index()
    {
        // $admin = Auth::guard('admin')->user();
        // echo 'hello  ' .  $admin->name . '<a href="' . route('admin.logout') . '"> logout</a>';
        return view('admin.dashboard');
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        session()->flush();
        return redirect()->route('admin.login');
    }
}
