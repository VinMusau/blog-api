<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash; 

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fields= $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|confirmed|min:8',
        ]);

        $user = User::create($fields);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|exists:users,email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([ 'message' => 'Incorrect credentials' ], 401);


           
            
        } else {
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'message' => 'Login Successful', 
                'user' => $user,
                'token' => $token
            ]);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'You Logged out']);

    }
    public function __invoke(Request $request)
    {
        //
    }
}
