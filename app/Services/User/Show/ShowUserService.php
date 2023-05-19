<?php declare(strict_types=1);

namespace App\Services\User\Show;

use App\ApiClient;
use App\Exceptions\ResourceNotFoundException;

class ShowUserService
{
    private ApiClient $client;

    public function __construct()
    {
        $this->client = new ApiClient();
    }
    public function execute(ShowUserRequest $request): ShowUserResponse
    {
        $user = $this->client->fetchUserById($request->getUserId());

        if ($user == null) {
            throw new ResourceNotFoundException('User' . $request->getUserId() . 'not found.');
        }

        $articles = $this->client->fetchArticlesByUser($user->getId());

        return new ShowUserResponse($user, $articles);
    }
}