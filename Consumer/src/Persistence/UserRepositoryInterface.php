<?php

namespace App\Persistence;

interface UserRepositoryInterface
{
    /**
     * @param $email
     * @return mixed
     */
    public function findByEmail($email);

    /**
     * @param $username
     * @return mixed
     */
    public function findByUsername($username);

    /**
     * @param $id
     * @return mixed
     */
    public function findById($id);

    /**
     * @param $data
     * @return mixed
     */
    public function addNewUser($data);

    /**
     * @param $username
     * @return mixed
     */
    public function changePassword($username);
}
