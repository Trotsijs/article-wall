<?php declare(strict_types=1);

namespace App\Services\Article\Show;

use App\ApiClient;
use App\Exceptions\ArticleNotFoundException;


class ShowArticleService
{
    private ApiClient $client;

    public function __construct()
    {
        $this->client = new ApiClient();
    }

    public function execute(ShowArticleRequest $request): ShowArticleResponse
    {
        $article = $this->client->fetchSingleArticle($request->getArticleId());
        if ($article == null) {
            throw new ArticleNotFoundException('Article' . $request->getArticleId() . 'not found.');
        }

        $comments = $this->client->fetchComments($article->getPostId());

        return new ShowArticleResponse($article, $comments);
    }
}