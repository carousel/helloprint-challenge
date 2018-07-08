<?php

namespace App\Persistence;

interface UserRepositoryInterface
{
    public function findByEmail($email);

    public function findByUsername($username);

    public function findById($id);

    public function addNewUser($data);

    public function changePassword($username);
}
