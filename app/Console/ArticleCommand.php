<?php declare(strict_types=1);

namespace App\Console;

use App\Services\Article\Show\ShowArticleRequest;
use App\Services\Article\Show\ShowArticleService;

class ArticleCommand
{
    private ShowArticleService $articleService;

    public function __construct()
    {
        $this->articleService = new ShowArticleService();
    }

    public function execute(int $articleId)
    {
        $response = $this->articleService->execute(new ShowArticleRequest($articleId));
        echo '================================================' . PHP_EOL;
        echo '[' . $response->getArticle()->getPostId() . ']';
        echo ' Title: ' . ucfirst($response->getArticle()->getTitle()) . PHP_EOL;
        echo '[#] Content: ' . ucfirst($response->getArticle()->getBody()) . PHP_EOL;
        echo 'Author: ' . $response->getArticle()->getAuthor() . PHP_EOL;
        echo '================================================' . PHP_EOL;

    }
}

