<?php declare(strict_types=1);

namespace App\Controllers;

use App\Core\Redirect;
use App\Core\TwigView;
use App\Exceptions\ValidatorException;
use App\Services\User\Register\RegisterRequest;
use App\Services\User\Register\RegisterService;
use App\Validation\RegisterFormValidation;

class RegisterController
{
    private RegisterService $registerService;
    private RegisterFormValidation $validator;

    public function __construct(RegisterService $registerService, RegisterFormValidation $validator)
    {

        $this->registerService = $registerService;
        $this->validator = $validator;
    }

    public function showForm(): TwigView
    {
        return new TwigView('register', []);
    }

    public function save(array $vars): Redirect
    {
        try {
            $this->validator->validateRegisterForm($_POST);

            $response = $this->registerService->execute(new RegisterRequest(
                    $_POST['name'],
                    $_POST['email'],
                    $_POST['password']
                )
            );

            $_SESSION['authId'] = $response->getUser()->getId();

            return new Redirect('/articles');

        } catch (ValidatorException $exception) {
            return new Redirect('/register');
        } catch (\Exception $exception) {
            return new Redirect('/register');
        }
    }

    public function logout(array $vars): Redirect
    {
        unset($_SESSION['authId']);

        return new Redirect('/');
    }
}
