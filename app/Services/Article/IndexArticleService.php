<?php declare(strict_types=1);

namespace App\Services\Article;

use App\ApiClient;

class IndexArticleService
{
    private ApiClient $client;

    public function __construct()
    {
        $this->client = new ApiClient();
    }
    public function execute(): array
    {
        return $this->client->fetchArticles();
    }
}