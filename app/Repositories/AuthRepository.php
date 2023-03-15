<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthRepository implements AuthRepositoryInterface
{

    public function me()
    {
        $user = Auth::user();
        $token = Auth::login($user);

        return response()->json([
            'request' => 'me',
            'status' => 'success',
            'user email' => $user->email,
            'user login' => $user->login,
            'token' => $token
        ]);
    }

    public function login(Request $request)
    {

        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::user();
        return response()->json([
            'status' => 'success',
            'user' => $user->email,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function register(Request $request)
    {
        if (Auth::user()) {
            $user = User::create([
                'name' => $request->name,
                'login' => $request->login,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'address' => $request->address,
                'avatar' => $request->avatar,
            ]);

            $token = Auth::login($user);
            return response()->json([
                'status' => 'success',
                'message' => 'User created successfully',
                'user' => $user,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'ye are already logged in',

        ]);
    }

    public function logout()
    {
        if (Auth::user()) {
            Auth::logout();
            return response()->json([
                'status' => 'success',
                'message' => 'Successfully logged out',

            ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'error',
        ]);    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }
}
