<?php declare(strict_types=1);

namespace App\Services\Article;

use App\Repositories\Article\ArticleRepository;
use App\Repositories\Article\JsonPlaceholderArticleRepository;

class IndexArticleService
{
    private ArticleRepository $articleRepository;

    public function __construct()
    {
        $this->articleRepository = new JsonPlaceholderArticleRepository();
    }
    public function execute(): array
    {
        return $this->articleRepository->all();
    }
}