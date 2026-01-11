<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;



class AuthController extends Controller
{
    

    /**
     * register
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        return response()->json(['message' => 'User registered successfully'], 201);
    }


    /**
     * login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);        
        $user = User::where('email', $request->email)->first();
        if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->json([
                    'message' => __('auth.failed')
                ], 401);
        }
        $token = $user->createToken('auth_token')->plainTextToken;
        //wallet
        $wallet = Wallet::where('id', $user['id'])->first();        
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
            'wallet' => $wallet,
        ]);
    }

    public function user(Request $request)
    {
        $user = $request->user();
        $wallet = Wallet::where('user_id', $user['id'])->first();
        return response()->json([
            'user' => $user,
            'wallet' => $wallet
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    }
}