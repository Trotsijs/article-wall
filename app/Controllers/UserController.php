<?php declare(strict_types=1);

namespace App\Controllers;

use App\ApiClient;
use App\Core\TwigView;

class UserController
{
    private ApiClient $client;

    public function __construct()
    {
        $this->client = new ApiClient();
    }

    public function index(): TwigView
    {
        return new TwigView('users', ['users' => $this->client->fetchUsers()]);
    }

    public function show(array $vars): TwigView
    {
        $userId = $vars['id'] ?? null;
        $user = $this->client->fetchUserById((int)$userId);
        $articles = $this->client->fetchArticlesByUser($user->getId());

        return new TwigView('singleUser', ['user' => $user, 'articles' => $articles]);
    }
}