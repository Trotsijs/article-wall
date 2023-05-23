<?php declare(strict_types=1);

namespace App\Services\User;


use App\Repositories\UserRepository;

class IndexUserService
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }
    public function execute(): array
    {
        return $this->userRepository->all();
    }
}