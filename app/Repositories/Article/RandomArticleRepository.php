<?php declare(strict_types=1);

namespace App\Repositories\Article;

use App\Models\Article;

class RandomArticleRepository implements ArticleRepository
{
    public function all(): array
    {
        $articles = [];
        for ($i = 0; $i < 10; $i++) {
            $articles[] = $this->getRandomArticle();
        }
        return $articles;
    }

    public function getById(int $id): ?Article
    {
        return $this->getRandomArticle();
    }

    public function getByUserId(int $userId): array
    {
        return [];
    }

    private function getRandomArticle(): Article
    {
        return new Article(
            1,
            'Article ' . rand(1, 100),
            'Article body',
            'https://placehold.co/800x400?text=Random',
            null,
            1,
        );
    }

    public function save(Article $article): void
    {
        // TODO: Implement save() method.
    }
}