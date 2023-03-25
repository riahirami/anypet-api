<?php

namespace App\Repositories;

use App\Http\Requests\Auth\AuthForgotPasswordeRequest;
use App\Http\Requests\Auth\AuthLoginRequest;
use App\Http\Requests\Auth\AuthRegistreRequest;
use App\Http\Requests\Auth\AuthResetPasswordeRequest;
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
        //        $token = Auth::login($user);
        return Auth::user();
    }

    public function login(AuthLoginRequest $request)
    {

        $credentials = $request->validated();
        $token = Auth::attempt($credentials);
        return $token;

    }

    public function register(AuthRegistreRequest $request)
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
            event(new Registered($user));
            return $user;
        }
    }

    public function logout()
    {
        if (Auth::user()) {
           return Auth::logout();
        }
        return null ;

    }

    public function refresh()
    {
        return Auth::refresh();
    }

    public function SendEmailVerification(EmailVerificationRequest $request)
    {
         $request->fulfill();
    }

    public function ResendEmailVerification(Request $request)
    {
            return   $request->user()->sendEmailVerificationNotification();

    }

    public function forgotPassword(AuthForgotPasswordeRequest $request)
    {
        $status = Password::sendResetLink(
            $request->validated()
        );
        if ($status == Password::RESET_LINK_SENT) {
            return $status;
        }
        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }

    public function resetPassword(AuthResetPasswordeRequest $request)
    {
        $status = Password::reset(
            $request->validated(),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();
                $user->tokens()->delete();
                event(new PasswordReset($user));
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return $status;
        }

        return null ;

    }

}
