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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthRepository
{

    /**
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function profile()
    {
        //        $token = Auth::login($user);
        return Auth::user();
    }

    /**
     * @param Request $request
     * @return string
     */
    public function setAvatar(Request $request)
    {

        $path = Storage::disk('avatars')->put("", $request->file('avatar'));
        $imageUrl = Storage::disk('avatars')->url($path);
        $connectedUser = User::findOrFail(Auth::id());
        $connectedUser->avatar = $imageUrl;
        $connectedUser->save();
        return $imageUrl;
    }

    /**
     * @param AuthLoginRequest $request
     * @return bool
     */
    public function login(AuthLoginRequest $request)
    {

        $credentials = $request->validated();
        $token = Auth::attempt($credentials);
        return $token;

    }

    /**
     * @param AuthRegistreRequest $request
     * @return void
     */
    public function register(AuthRegistreRequest $request)
    {
        if (!Auth::user()) {
            $user = User::create([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'address' => $request->address,
                'avatar' => "default avatar",
            ]);
            event(new Registered($user));
            return $user;
        }
    }

    /**
     * @param AuthRegistreRequest $request
     * @return \Illuminate\Contracts\Auth\Authenticatable|\Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        $user->update([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return $user;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        if (Auth::check()) {
            Auth::logout();

            return response()->json([
                'message' => 'Successfully logged out',
            ], 200);
        }
        return response()->json([
            'message' => 'error',
        ], 500);

    }

    /**
     * @return mixed
     */
    public function refresh()
    {
        return Auth::refresh();
    }

    /**
     * @param EmailVerificationRequest $request
     * @return void
     */
    public function SendEmailVerification(EmailVerificationRequest $request)
    {
        $request->fulfill();
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function ResendEmailVerification(Request $request)
    {
        return $request->user()->sendEmailVerificationNotification();

    }

    /**
     * @param AuthForgotPasswordeRequest $request
     * @return string
     * @throws ValidationException
     */
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

    /**
     * @param AuthResetPasswordeRequest $request
     * @return mixed|null
     */
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

        return null;

    }

}
