<?php declare(strict_types=1);

namespace App\Services\User;


use App\Repositories\User\JsonPlaceholderUserRepository;
use App\Repositories\User\UserRepository;

class IndexUserService
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new JsonPlaceholderUserRepository();
    }
    public function execute(): array
    {
        return $this->userRepository->all();
    }
}