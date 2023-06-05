<?php

namespace App\Services\User\Login;

use App\Models\User;
use App\Repositories\User\UserRepository;

class LoginUserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(string $email, string $password): ?User
    {
        return $this->userRepository->login($email, $password);
    }
}