<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthRepository
{

    public function profile()
    {
        $user = Auth::user();
        $token = Auth::login($user);

        if( $token){

        return response()->json([
            'request' => 'profile',
            'status' => 'success',
            'user email' => Auth::user()->email,
            'token' => $token
        ]);
        }else{
            return response()->json([
                'request' => 'me',
                'status' => 'error'

            ]);
        }
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
        if (!Auth::user()) {
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
            event(new Registered($user));
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

    public function SendEmailVerification (EmailVerificationRequest $request) {
        $request->fulfill();
        return "you are redirect by email verify link ";
    }

    public function ResendEmailVerification (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return "Verification link re-sent!";
    }

    public function forgotPassword(Request $request){
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status == Password::RESET_LINK_SENT) {
            return [
                'status' => __($status)
            ];
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }

    public function resetPassword(Request $request){
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
//                    'password' => $request->password,
                    'remember_token' => Str::random(60),
                ])->save();

                $user->tokens()->delete();

                event(new PasswordReset($user));
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return response([
                'message'=> 'Password reset successfully'
            ]);
        }

        return response([
            'message'=> __($status)
        ], 500);

    }

}
