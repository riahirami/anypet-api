<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
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

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        return $this->auth->login($request);

    }

    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|min:4|max:255',
            'login' => 'required|string|min:4|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:5',
            'phone' => 'required|string|min:8|max:255',
            'address' => 'required|string|max:255',
            'avatar' => 'required|string|max:255',

        ]);
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
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

       return $this->auth->forgotPassword($request);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', RulesPassword::defaults()],
        ]);

        return $this->auth->resetPassword($request);

    }

}
