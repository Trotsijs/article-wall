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
        $article = $this->client->fetchSingleArticle((int)implode('', $vars));
        $comments = $this->client->fetchComments($article->getPostId());

        return new TwigView('singleArticle', ['article' => $article, 'comments' => $comments]);
    }

    public function singleUser(array $vars): TwigView
    {
        $user = $this->client->fetchUserById((int)implode('', $vars));
        $articles = $this->client->fetchArticlesByUser($user->getId());

        return new TwigView('singleUser', ['user' => $user, 'articles' => $articles]);
    }
}