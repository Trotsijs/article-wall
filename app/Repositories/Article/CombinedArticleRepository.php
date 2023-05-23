<?php declare(strict_types=1);

namespace App\Repositories\Article;

use App\Models\Article;

class CombinedArticleRepository implements ArticleRepository
{
    private ArticleRepository $jsonPlaceholderArticleRepository;
    private ArticleRepository $randomArticleRepository;
    public function __construct()
    {
        $this->jsonPlaceholderArticleRepository = new JsonPlaceholderArticleRepository();
        $this->randomArticleRepository = new RandomArticleRepository();
    }

    public function all(): array
    {
        $jsonArticles = $this->jsonPlaceholderArticleRepository->all();
        $randomArticles = $this->randomArticleRepository->all();
        return array_merge($jsonArticles, $randomArticles);
    }

    public function getById(int $id): ?Article
    {
        return $this->jsonPlaceholderArticleRepository->getById($id);
    }

    public function getByUserId(int $userId): array
    {
        return $this->randomArticleRepository->getByUserId($userId);
    }
}