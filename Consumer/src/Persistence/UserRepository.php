<?php

namespace App\Persistence;

use App\Services\Mail;

class UserRepository
{
    public function __construct(DB $db)
    {
        $this->db = $db;
    }

    public function findByEmail($type, $email)
    {
        return $this->db->get($type, $email);
    }

    public function findByUsername($username)
    {
        return $this->db->get($username);
    }

    public function findById($type, $id)
    {
        return $this->db->get($type, $username);
    }

    public function addNewUser($data)
    {
        $this->db->create($data);
    }

    public function changePassword($data)
    {
        $password = uniqid();
        $column = 'recovery';
        $username = $data['username'];
        $this->db->update($column, $password, $username);

        $email = $this->findByUsername($username);

        $subject = 'You requested to change password';
        $message = "Your new password is: " . $password . "<br>login: <a href=\"http://client:8088\">here</a>";
        new Mail($username, $email, $subject, $message);
    }
    public function session($data)
    {
        $column = 'logged';
        $status = 1;
        $username = $data['username'];
        $password = $data['password'];
        $user = $this->findByUsernameAndPassword($username, $password);
        if ($user != null) {
            $this->db->update($column, $status, $username);
        }
    }
    public function findByUsernameAndPassword($username, $password)
    {
        return $this->db->find($username, $password);
    }
}
