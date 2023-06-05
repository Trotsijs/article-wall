<?php declare(strict_types=1);

namespace App\Services\User\Register;

use App\Models\User;
use App\Repositories\User\UserRepository;
use http\Exception\InvalidArgumentException;

class RegisterService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(RegisterRequest $request): RegisterResponse
    {
        $user = new User(
            $request->getName(),
            $request->getEmail(),
            password_hash($request->getPassword(), PASSWORD_DEFAULT)
        );

        $this->userRepository->save($user);

        return new RegisterResponse($user);
    }
}