<?php

namespace App\Validation;

use App\Exceptions\ValidatorException;
use App\Repositories\User\UserRepository;

class LoginFormValidation
{
    private UserRepository $userRepository;
    private array $errors = [];
    private array $fields = [];

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function validateLoginForm(array $fields = []): void
    {
        $this->fields = $fields;

        foreach ($fields as $field => $value) {
            $methodName = 'validate' . ucfirst($field);

            if (method_exists($this, $methodName)) {
                $this->{$methodName}();
            }
        }

        if (count($this->errors) > 0) {

            $_SESSION['errors'] = $this->errors;

            throw new ValidatorException('Form validation has failed.');
        }
    }

    private function validateEmail()
    {
        $email = $this->fields['email'];

        if ($email === $user['email']) {
            $this->errors['email'][] = 'Email field is required';
        }

        $user = $this->userRepository->getByEmail($email);

        if ($user !== null) {
            $this->errors['email'][] = 'Email already taken.';
        }

    }

    private function validatePassword()
    {
        $password = $this->fields['password'];

        if (!isset($password) || strlen($password) < 1) {
            $this->errors['password'][] = 'Password field is required';
        }
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

}