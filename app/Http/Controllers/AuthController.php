<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function login(Request $req)
    {
        $req->validate([
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required']
        ]);
        if (!Auth::attempt($req->only('email', 'password'))) {
            return response()->json([
                'status' => false,
                'message' => "invalid email or password",
            ], 401);
        }
        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => $user,
            'token' => $token
        ]);
    }
    public function register(Request $req)
    {
        $req->validate([
            'name' => ["required", 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        $user = User::create([
            'name' => $req->name,
            'email' => $req->email,
            'password' => Hash::make($req->password),

        ]);
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'created account',
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function logOut(Request $req)
    {
        $req->user()->currentAccessToken()->delete();
        return response()->json([
            'status' => true,
            'message' => 'logged out'
        ], 200);
    }

    public function deleteAccount(Request $req)
    {
        $user = $req->user();
        $user->delete();
        return response()->json([
            'status' => true,
            'message' => 'deleted account'
        ], 200);
    }
}
