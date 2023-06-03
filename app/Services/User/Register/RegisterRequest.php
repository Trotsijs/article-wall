<?php declare(strict_types=1);

namespace App\Services\User\Register;

class RegisterRequest
{
    private string $name;
    private string $email;
    private string $password;
    private string $passwordConfirmation;

    public function __construct(
        string $name,
        string $email,
        string $password,
        string $passwordConfirmation
    ) {

        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->passwordConfirmation = $passwordConfirmation;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getPasswordConfirmation(): string
    {
        return $this->passwordConfirmation;
    }
}