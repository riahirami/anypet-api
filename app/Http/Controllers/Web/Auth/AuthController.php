<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthForgotPasswordeRequest;
use App\Http\Requests\Auth\AuthLoginRequest;
use App\Http\Requests\Auth\AuthRegistreRequest;
use App\Http\Requests\Auth\AuthResetPasswordeRequest;
use App\Repositories\AuthRepository;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Validation\Rules\Password as RulesPassword;

class AuthController extends Controller
{

    private $auth;

    public function __construct(AuthRepository $auth)
    {
        $this->auth = $auth;
    }

    public function profile()
    {
        $user = $this->auth->profile();

        if ($user) {

            return response()->json([
                'message' => 'success',
                'user email' => Auth::user()->email,
            ], 201);
        }
        return response()->json([
            'message' => 'error access profile'

        ], 500);
    }

    public function login(AuthLoginRequest $request)
    {
        $validatedRequest = $request->validated();
        $user = $this->auth->login($request);
        if ($user) {
            return response()->json([
                'user' => $user,
                'message' => 'ye are logged in',

            ], 201);
        }
        return response()->json([
            'message' => 'error sign in',

        ], 500);

    }

    public function register(AuthRegistreRequest $request): JsonResponse
    {
        $validatedRequest = $request->validated();
        $user = $this->auth->register($request);
        $token = Auth::login($user);

        if ($user) {
            return response()->json([
                'message' => 'User created successfully',
                'user' => $user,
                'token' => $token

            ], 201);
        }
        return response()->json([
            'message' => 'sign up unsuccessfully',

        ], 500);
    }

    public function logout()
    {
        $user = Auth::user();
        if ($user) {
            $this->auth->logout();
            return response()->json([
                'message' => 'Successfully logged out',
            ], 201);
        }
        return response()->json([
            'message' => 'error',
        ], 500);
    }

    public function refresh()
    {
        $refresh = $this->auth->refresh();
        if ($refresh) {
            return response()->json([
                'message' => 'your session has been refreshed successfully',
            ], 201);
        }
        return response()->json([
            'message' => 'error',
        ], 500);
    }

    public function SendEmailVerification(EmailVerificationRequest $request)
    {
        if(!$request->user()->email_verified_at) {
        $this->auth->SendEmailVerification($request);
            return response()->json([
                'message' => 'you are redirect by email verify link and your account is now verified',
            ], 201);
        }
        return response()->json([
            'message' => 'error verification, try again later',
        ], 500);
    }

    public function ResendEmailVerification(Request $request)
    {
        if(!$request->user()->email_verified_at) {
        $this->auth->ResendEmailVerification($request);
            return response()->json([
                'message' => 'you re email verification was resend again',
            ], 201);
        }
        return response()->json([
            'message' => 'error ! your account is already verified',
        ], 500);
    }

    public function forgotPassword(AuthForgotPasswordeRequest $request)
    {
        $validatedRequest = $request->validated();
        $forgotPassword=$this->auth->forgotPassword($request);
         if($forgotPassword){
             return response()->json([
                 'message' => 'reset password link has been send, check your email',
             ], 201);
         }
        return response()->json([
            'message' => 'error to send reset password link, try again later',
        ], 500);
    }

    public function reset(AuthResetPasswordeRequest $request)
    {
        $validatedRequest = $request->validated();
        $reset= $this->auth->resetPassword($request);

        if($reset){
            return response()->json([
                'message' => 'password reset successfully  ',
            ], 201);
        }
        return response()->json([
            'message' => 'error to reset password, try again later',
        ], 500);
    }

}
