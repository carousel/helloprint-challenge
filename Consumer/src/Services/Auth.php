<?php

namespace App\Services;
use App\Persistence\UserRepository;

class Auth
{
    private $userRepo;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepo = $userRepository;
    }
    
    public function login($data)
    {
        return $this->userRepo->session($data);
    }
        
}
