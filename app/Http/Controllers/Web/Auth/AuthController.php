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

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function updateAvatar(Request $request)
    {
        try {
            if ($request->hasFile('avatar')) {
                $avatar = $this->auth->setAvatar($request);
                return $this->returnSuccessResponse(Response::HTTP_CREATED, ['data' => $avatar]);
            } else {
                return $this->returnSuccessResponse(Response::HTTP_BAD_REQUEST, ['message' => 'no avatar selected']);

            }
        } catch (HttpClientException $e) {
            return $this->returnErrorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, trans($e->getMessage()));

        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function updateInformation(Request $request)
    {
        try {
            $user = $this->auth->update($request);
            return $this->returnSuccessResponse(Response::HTTP_CREATED, ['data' => $user]);
        } catch (HttpClientException $e) {
            return $this->returnErrorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, trans($e->getMessage()));

        }
    }

    /**
     * @return JsonResponse
     */
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

    /**
     * @param AuthLoginRequest $request
     * @return JsonResponse
     */
    public function login(AuthLoginRequest $request)
    {
        try {
            $credentials = $request->validated();

            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $token = $this->auth->login($request);
                return $this->returnSuccessResponse(Response::HTTP_OK, [
                    'user' => $user,
                    'token' => $token,
                    'message' => 'You are logged in',
                ]);
            } else {
                return $this->returnErrorResponse(Response::HTTP_UNAUTHORIZED, 'Invalid credentials');
            }
        } catch (\Exception $e) {
            return $this->returnErrorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, trans('error sign in'));
        }
    }

    /**
     * @param AuthRegistreRequest $request
     * @return JsonResponse
     */
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

    /**
     * @return JsonResponse
     */
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

    /**
     * @return JsonResponse
     */
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

    /**
     * @param EmailVerificationRequest $request
     * @return JsonResponse
     */
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

    /**
     * @param Request $request
     * @return JsonResponse
     */
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

    /**
     * @param AuthForgotPasswordeRequest $request
     * @return JsonResponse
     * @throws ValidationException
     */
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

    /**
     * @param AuthResetPasswordeRequest $request
     * @return JsonResponse
     */
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
