<?php

namespace App\Controllers;

use App\Core\Redirect;
use App\Core\TwigView;
use App\Services\User\Login\LoginUserService;
use function DI\string;

class LoginController
{
    private LoginUserService $loginUserService;

    public function __construct(LoginUserService $loginUserService)
    {
        $this->loginUserService = $loginUserService;
    }

    public function showForm(): TwigView
    {
        return new TwigView('login', []);
    }

    public function login(array $vars): Redirect
    {
        try {

            $email = $_POST['email'];
            $password = $_POST['password'];


            $response = $this->loginUserService->execute($email, $password);

            $_SESSION['authId'] = $response->getId();

            return new Redirect('/articles');


        } catch (\Exception $exception) {

        }

        return new Redirect('/register');
    }

    public function logout(array $vars): Redirect
    {
        unset($_SESSION['authId']);
        return new Redirect('/');
    }
}