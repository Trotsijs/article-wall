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
        $userExists = $this->userRepository->getByEmail($request->getEmail());

        if ($userExists !== null) {
            throw new InvalidArgumentException('User already registered');
        }

        if ($request->getPassword() !== $request->getPasswordConfirmation()) {
            throw new InvalidArgumentException('Password does not match');
        }

        $user = new User(
            $request->getName(),
            $request->getEmail(),
            $request->getPassword()
        );

        $this->userRepository->save($user);

        return new RegisterResponse($user);
    }
}