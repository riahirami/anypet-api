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
//        $this->middleware(['auth:api', 'verified'], ['except' => ['login', 'register', 'SendEmailVerification','ResendEmailVerification']]);
        $this->auth = $auth;
    }

    public function profile()
    {
        return $this->auth->profile();
    }

    public function login(AuthLoginRequest $request)
    {
        $validatedRequest = $request->validated();
        return $this->auth->login($request);

    }

    public function register(AuthRegistreRequest $request): JsonResponse
    {
        $validatedRequest = $request->validated();
        return $this->auth->register($request);
    }

    public function logout()
    {
        return $this->auth->logout();
    }

    public function refresh()
    {
        return $this->auth->refresh();
    }

    public function SendEmailVerification(EmailVerificationRequest $request){
        return $this->auth->SendEmailVerification($request);
    }

    public function ResendEmailVerification(Request $request){
        return $this->auth->ResendEmailVerification($request);
    }
    public function forgotPassword(AuthForgotPasswordeRequest $request)
    {
        $validatedRequest = $request->validated();
        return $this->auth->forgotPassword($request);
    }

    public function reset(AuthResetPasswordeRequest $request)
    {
        $validatedRequest = $request->validated();


        return $this->auth->resetPassword($request);

    }

}
