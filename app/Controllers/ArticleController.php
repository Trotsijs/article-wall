<?php declare(strict_types=1);

namespace App\Controllers;

use App\ApiClient;
use App\Core\TwigView;

class ArticleController
{
    private ApiClient $client;

    public function __construct()
    {
        $this->client = new ApiClient();
    }

    public function index(): TwigView
    {
        return new TwigView('articles', ['articles' => $this->client->fetchArticles()]);
    }

    public function users(): TwigView
    {
        return new TwigView('users', ['users' => $this->client->fetchUsers()]);
    }

    public function singleArticle(array $vars): TwigView
    {
        $articleId = $vars['id'] ?? null;
        $article = $this->client->fetchSingleArticle((int) $articleId);
        $comments = $this->client->fetchComments($article->getPostId());

        return new TwigView('singleArticle', ['article' => $article, 'comments' => $comments]);
    }

    public function singleUser(array $vars): TwigView
    {
        $userId = $vars['id'] ?? null;
        $user = $this->client->fetchUserById((int) $userId);
        $articles = $this->client->fetchArticlesByUser($user->getId());

        return new TwigView('singleUser', ['user' => $user, 'articles' => $articles]);
    }
}