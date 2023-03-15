<?php

namespace App\Repositories;


use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    protected $user = null;

    public function getAllUsers()
    {
        return User::all();
    }

    public function getUserById($id)
    {
        try {
            $user = User::find($id);
            if($user) {

            return response()->json([
                'message' => 'User find',
                'code' => 200,
                'error' => false,
                'results' => $user
            ], 201);
            }
            else {
                return response()->json([
                    'message' => "no user found",
                    'error' => true,
                    'code' => 500
                ], 500);
            }

        } catch(\Exception $e) {
            return response()->json([
                'message' => "$e->getMessage()",
                'error' => true,
                'code' => 500
            ], 500);
        }

    }

    public function createOrUpdate( $id = null, $collection = [] )
    {
        if(is_null($id)) {
            $user = new User;
            $user->name = $collection['name'];
            $user->email = $collection['email'];
            $user->login = $collection['login'];
            $user->phone = $collection['phone'];
            $user->address = $collection['address'];
            $user->avatar = $collection['avatar'];
            $user->password = Hash::make('password');
            return $user->save();
        }
        $user = User::find($id);
        $user->name = $collection['name'];
        $user->login = $collection['login'];
        $user->email = $collection['email'];
        $user->phone = $collection['phone'];
        $user->address = $collection['address'];
        $user->avatar = $collection['avatar'];
        return $user->save();
    }

    public function deleteUser($id)
    {
        return User::find($id)->delete();
    }

    public function createUser($u)
    {
        try {
            $newUser = new User;
            $newUser->name = $u->name;
            $newUser->login = $u->login;
            $newUser->email = $u->email;
            $newUser->password = \Hash::make($u->password);
            $newUser->phone = $u->phone;
            $newUser->address = $u->address;
            $newUser->avatar = $u->avatar;
            // $newUser->email = preg_replace('/\s+/', '', strtolower($request->email));
            $newUser->save();

            return response()->json([
                'message' => 'User created',
                'code' => 200,
                'error' => false,
                'results' => $newUser
            ], 201);
        } catch(\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'error' => true,
                'code' => 500
            ], 500);
        }
    }
}
