<?php

namespace App\Validation;

use App\Exceptions\ValidatorException;
use App\Repositories\User\UserRepository;

class RegisterFormValidation
{
    private UserRepository $userRepository;
    private array $errors = [];
    private array $fields = [];

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function validateRegisterForm(array $fields = []): void
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

        if (!isset($email) || strlen($email) < 1) {
            $this->errors['email'][] = 'Email field is required';
        }

        $user = $this->userRepository->getByEmail($email);

        if ($user !== null) {
            $this->errors['email'][] = 'Email already taken.';
        }

    }

    private function validateName()
    {
        $name = $this->fields['name'];

        if (!isset($name) || strlen($name) < 1) {
            $this->errors['name'][] = 'Name field is required';
        }
    }

    private function validatePassword()
    {
        $password = $this->fields['password'];
        $passwordConfirmation = $this->fields['password_confirmation'];

        if (!isset($password) || strlen($password) < 1) {
            $this->errors['password'][] = 'Password field is required';
        }

        if (!isset($passwordConfirmation) || strlen($passwordConfirmation) < 1) {
            $this->errors['password_confirmation'][] = 'Password Confirmation field is required';
        }

        if ($password !== $passwordConfirmation) {
            $this->errors['password'] = 'Passwords dont match';
        }
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

}