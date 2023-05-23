<?php declare(strict_types=1);

namespace App\Services\Article;

use App\Repositories\ArticleRepository;

class IndexArticleService
{
    private ArticleRepository $articleRepository;

    public function __construct()
    {
        $this->articleRepository = new ArticleRepository();
    }
    public function execute(): array
    {
        return $this->articleRepository->all();
    }
}