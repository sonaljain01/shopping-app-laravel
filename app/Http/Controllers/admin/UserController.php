<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::where('role', 1)->latest();
        if ($request->get('keyword') != "") {
            $users = $users->where('users.username', 'like', '%' . $request->keyword . '%');
            $users = $users->orWhere('users.email', 'like', '%' . $request->keyword . '%');

        }
        $users = $users->paginate(10);

        return view('admin.users.list', [
            'users' => $users
        ]);
    }

    public function create(Request $request)
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'status' => 'required|in:1,0',
        ]);



        if ($validator->passes()) {
            $user = new User();
            $user->username = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->status = $request->status;
            $user->save();

            $request->session()->flash('success', 'User created successfully');
            return redirect()->route('users.index');

        } else {
            $request->session()->flash('error', $validator->errors()->first());
            return redirect()->route('users.index');
        }
    }

    public function edit($id, Request $request)
    {
        $user = User::find($id);
        if ($user == null) {
            $request->session()->flash('error', 'User not found');
            return redirect()->route('users.index');
        }

        return view('admin.users.edit', [
            'user' => $user
        ]);
    }

    public function update($id, Request $request)
    {

        $user = User::where('id', $id)->first();

        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id . ',id',
            'status' => 'required|in:1,0',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $user->username = $request->name;
            $user->email = $request->email;
            if ($request->password != null) {
                $user->password = bcrypt($request->password);
            }
            $user->status = $request->status;
            $user->save();
            $request->session()->flash('success', 'User updated successfully');
            return redirect()->route('users.index');
        } else {
            $request->session()->flash('error', $validator->errors()->first());
            return redirect()->route('users.index');
        }

    }

    public function destroy($userId, Request $request)
    {
        $user = User::find($userId);
        if (empty($user)) {
            return redirect()->route('users.index');
        }


        $user->delete();

        $request->session()->flash('success', 'User deleted successfully');

        return response()->json([
            'status' => true,
            'message' => 'User deleted successfully'
        ]);
    }

    public function loginAsCustomer($id)
    {
        // Find the user by ID
        $user = User::findOrFail($id);

        // Save current admin ID (or other relevant data) in the session to restore later
        session(['original_admin_id' => auth()->id()]);

        // Log in as the selected user
        \Auth::login($user);

        // Redirect to a customer dashboard or profile page
        return redirect()->route('customer.profile', ['user' => $user->id]);
    }

    public function showProfile($id)
    {
        // Fetch customer information and orders
        $customer = User::with('orders')->findOrFail($id);

        return view('customer.profile', compact('customer'));
    }

    public function restoreAdminSession()
    {
        $adminId = session('original_admin_id');
        \Auth::loginUsingId($adminId);
        session()->forget('original_admin_id');

        return redirect()->route('users.index');
    }

}
