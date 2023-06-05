<?php declare(strict_types=1);

namespace App\Controllers;

use App\Core\TwigView;
use App\Exceptions\ResourceNotFoundException;
use App\Repositories\User\UserRepository;
use App\Services\User\Index\IndexUserService;
use App\Services\User\Show\ShowUserRequest;
use App\Services\User\Show\ShowUserService;

class UserController
{

    private ShowUserService $showUserService;
    private IndexUserService $indexUserService;

    public function __construct(ShowUserService $showUserService, IndexUserService $indexUserService)
    {

        $this->showUserService = $showUserService;
        $this->indexUserService = $indexUserService;
    }

    public function index(): TwigView
    {
        $users = $this->indexUserService->execute();

        return new TwigView('users', ['users' => $users]);
    }

    public function show(array $vars): ?TwigView
    {
        try {
            $userId = $vars['id'] ?? null;

            $response = $this->showUserService->execute(new ShowUserRequest((int)$userId));

            return new TwigView('singleUser', [
                'user' => $response->getUser(),
                'articles' => $response->getArticles(),
            ]);
        } catch (ResourceNotFoundException $exception) {
            return new TwigView('notFound', []);
        }
    }
}