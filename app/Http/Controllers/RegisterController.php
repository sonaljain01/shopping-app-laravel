<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use Hash;
use App\Models\User;
class RegisterController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ];
        $register = User::create($data);

        // Response
        if ($register) {
            

            return response()->json([
                'status' => true,
                'message' => 'User registered successfully, Please verify Your Email',
                'data' => $register,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'User not created',
        ]);
    }

        
}
