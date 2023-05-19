<?php declare(strict_types=1);

namespace App\Controllers;

use App\Core\TwigView;
use App\Exceptions\ResourceNotFoundException;
use App\Services\User\IndexUserService;
use App\Services\User\Show\ShowUserRequest;
use App\Services\User\Show\ShowUserService;

class UserController
{
    public function index(): TwigView
    {
        $service = new IndexUserService();
        $users = $service->execute();

        return new TwigView('users', ['users' => $users]);
    }

    public function show(array $vars): ?TwigView
    {
        try {
            $userId = $vars['id'] ?? null;
            $service = new ShowUserService();
            $response = $service->execute(new ShowUserRequest((int)$userId));

            return new TwigView('singleUser', [
                'user' => $response->getUser(),
                'articles' => $response->getArticles(),
            ]);
        } catch (ResourceNotFoundException $exception) {
            return null; //  add TwigView not found page
        }
    }
}