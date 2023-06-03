<?php declare(strict_types=1);

namespace App\Console;

use App\Services\Article\Index\IndexArticleService;

class ArticlesCommand
{
    private IndexArticleService $articlesService;

    public function __construct()
    {
        $this->articlesService = new IndexArticleService();
    }

    public function execute()
    {
        $response = $this->articlesService->execute();
    }
}