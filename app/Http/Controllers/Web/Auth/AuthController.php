<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\AuthRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
class AuthController extends Controller
{

    private $auth;

    public function __construct(AuthRepositoryInterface $auth)
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
        $this->auth = $auth;
    }

    public function me()
    {
       return  $this->auth->me();
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

}
