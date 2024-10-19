<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Auth;
class AdminLoginController extends Controller
{
    public function index()
    {
        return view('admin.login');
    }

    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if($validator->passes()){
            if(Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password],
   $request->get('remember'))) {

                $admin = Auth::guard('admin')->user();
                if($admin->role == 0){
                    return redirect()->route('admin.dashboard');

                } else {
                    Auth::guard('admin')->logout();
                    return redirect()->route('admin.login')->withErrors(['error' => 'You are not authorized to access dmin panel']);
                }
            } else {
                return redirect()->route('admin.login')->withErrors(['error' => 'Invalid email or password']);
            }
        }
        else{
            return redirect()->route('admin.login')->withErrors($validator)
                ->withInput($request->only('email'));
        }
    }

    
}
