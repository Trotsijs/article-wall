<?php declare(strict_types=1);

namespace App\Controllers;

use App\Core\Redirect;
use App\Core\TwigView;
use App\Services\User\Register\RegisterRequest;
use App\Services\User\Register\RegisterService;

class RegisterController
{
    private RegisterService $registerService;

    public function __construct(RegisterService $registerService)
    {

        $this->registerService = $registerService;
    }

    public function showForm(): TwigView
    {
        return new TwigView('register', []);
    }

    public function save(array $vars): Redirect
    {
        try {

            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $passwordConfirmation = $_POST['password_confirmation'];

            $response = $this->registerService->execute(new RegisterRequest(
                    $name, $email, $password, $passwordConfirmation
                )
            );

            var_dump($response->getUser());die;

        } catch (\Exception $exception) {

        }

        return new Redirect('/register');
    }
}
