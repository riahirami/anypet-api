<?php

namespace App\Repositories;

use http\Env\Request;

interface UserRepositoryInterface
{
    public function getAllUsers();

    public function createUser($user);
    public function getUserById($id);

    public function createOrUpdate( $id = null, $collection = [] );

    public function deleteUser($id);
}
