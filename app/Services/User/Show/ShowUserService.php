<?php declare(strict_types=1);

namespace App\Services\User\Show;

use App\Exceptions\ResourceNotFoundException;
use App\Models\Article;
use App\Repositories\Article\ArticleRepository;
use App\Repositories\Article\JsonPlaceholderArticleRepository;
use App\Repositories\User\JsonPlaceholderUserRepository;
use App\Repositories\User\UserRepository;

class ShowUserService
{
    private UserRepository $userRepository;
    private ArticleRepository $articleRepository;

    public function __construct()
    {
        $this->userRepository = new JsonPlaceholderUserRepository();
        $this->articleRepository = new JsonPlaceholderArticleRepository();
    }

    public function execute(ShowUserRequest $request): ShowUserResponse
    {
        $user = $this->userRepository->getById($request->getUserId());

        if ($user == null) {
            throw new ResourceNotFoundException('User' . $request->getUserId() . 'not found.');
        }

        $articles = $this->articleRepository->getByUserId($user->getId());

        /** @var Article $article */
        foreach ($articles as $article) {
            $article->setAuthor($this->userRepository->getById($article->getAuthorId()));
        }

        return new ShowUserResponse($user, $articles);
    }
}