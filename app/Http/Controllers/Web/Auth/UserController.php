<?php

namespace App\Http\Controllers\Web\Auth;

use App\Repositories\UserRepositoryInterface;
use Illuminate\Http\Request;
use http\Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

use App\Models\User;

class UserController extends \App\Http\Controllers\Controller
{
    private $user;

    public function __construct(UserRepositoryInterface $user)
    {
        $this->user = $user;
    }

    public function showUsers()
    {
        try {
            $users = $this->user->getAllUsers();

            return $users;
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function registreUser(Request $request):JsonResponse
    {

        $userDetails = $request->all();
        $newUser = new User();

        $newUser->name=$request->input('name');
        $newUser->login=$request->input('login');
        $newUser->email=$request->input('email');
        $newUser->password=$request->input('password');
        $newUser->phone=$request->input('phone');
        $newUser->address=$request->input('address');
        $newUser->avatar=$request->input('avatar');

        return response()->json(
            [
                $this->user->createUser($newUser)
            ],
            Response::HTTP_CREATED
        );

    }

    public function getUser($id)
    {
        $user = $this->user->getUserById($id);
        return $user;
    }

    public function saveUser(Request $request, $id = null)
    {
        $collection = $request->except(['_token','_method']);

        if( ! is_null( $id ) )
        {
            $this->user->createOrUpdate($id, $collection);
        }
        else
        {
            $this->user->createOrUpdate($id = null, $collection);
        }

        return redirect()->route('user.list');
    }

    public function deleteUser($id)
    {


        return $this->user->deleteUser($id);
    }
}
