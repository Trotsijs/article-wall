<?php declare(strict_types=1);

namespace App\Services\Article;

use App\Repositories\Article\ArticleRepository;
use App\Repositories\Article\CombinedArticleRepository;
use App\Repositories\User\JsonPlaceholderUserRepository;
use App\Repositories\User\UserRepository;

class IndexArticleService
{
    private ArticleRepository $articleRepository;
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->articleRepository = new CombinedArticleRepository();
        $this->userRepository = new JsonPlaceholderUserRepository();
    }

    public function execute(): array
    {
        $articles = $this->articleRepository->all();

        foreach ($articles as $article) {
            $author = $this->userRepository->getById($article->getAuthorId());
            $article->setAuthor($author);
        }

        return $articles;
    }
}