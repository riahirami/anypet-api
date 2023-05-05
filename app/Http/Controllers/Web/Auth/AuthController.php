<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthForgotPasswordeRequest;
use App\Http\Requests\Auth\AuthLoginRequest;
use App\Http\Requests\Auth\AuthRegistreRequest;
use App\Http\Requests\Auth\AuthResetPasswordeRequest;
use App\Http\Traits\GlobalTrait;
use App\Repositories\AuthRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
    use GlobalTrait;

    public function __construct(AuthRepository $auth)
    {
        $this->auth = $auth;
    }

    public function updateAvatar(Request $request)
    {
        try {
            if ($request->hasFile('avatar')) {
                $avatar = $this->auth->setAvatar($request);
                return $this->returnSuccessResponse(Response::HTTP_CREATED, ['data' => $avatar]);
            }
            else{
                return $this->returnSuccessResponse(Response::HTTP_BAD_REQUEST, ['message'=>'no avatar selected']);

            }
        } catch (HttpClientException $e) {
               return $this->returnErrorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, trans($e->getMessage()));

        }
    }

    public function profile()
    {
        $user = $this->auth->profile();

        if ($user->hasVerifiedEmail()) {

            return response()->json([
                'message' => 'success',
                'user' => Auth::user(),
            ], 201);
        }
        return response()->json([
            'message' => 'error access profile, check your email to verify your account !'

        ], 500);
    }

    public function login(AuthLoginRequest $request)
    {
        $validatedRequest = $request->validated();
        $user = $this->auth->login($request);
        if ($user) {
            return response()->json([
                'name' => Auth::user()->name,
                'token' => $user,
                'message' => 'ye are logged in',

            ], 200);
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
            ], 200);
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
            ], 200);
        }
        return response()->json([
            'message' => 'error',
        ], 500);
    }

    public function SendEmailVerification(EmailVerificationRequest $request)
    {
        if (!$request->user()->email_verified_at) {
            $this->auth->SendEmailVerification($request);
            return response()->json([
                'message' => 'you are redirect by email verify link and your account is now verified',
            ], 200);
        }
        return response()->json([
            'message' => 'error verification, try again later',
        ], 500);
    }

    public function ResendEmailVerification(Request $request)
    {
        if (!$request->user()->email_verified_at) {
            $this->auth->ResendEmailVerification($request);
            return response()->json([
                'message' => 'you re email verification was resend again',
            ], 200);
        }
        return response()->json([
            'message' => 'error ! your account is already verified',
        ], 500);
    }

    public function forgotPassword(AuthForgotPasswordeRequest $request)
    {
        $validatedRequest = $request->validated();
        $forgotPassword = $this->auth->forgotPassword($request);
        if ($forgotPassword) {
            return response()->json([
                'message' => 'reset password link has been send, check your email',
            ], 200);
        }
        return response()->json([
            'message' => 'error to send reset password link, try again later',
        ], 500);
    }

    public function reset(AuthResetPasswordeRequest $request)
    {
        $validatedRequest = $request->validated();
        $reset = $this->auth->resetPassword($request);

        if ($reset) {
            return response()->json([
                'message' => 'password reset successfully  ',
            ], 201);
        }
        return response()->json([
            'message' => 'error to reset password, try again later',
        ], 500);
    }

}
