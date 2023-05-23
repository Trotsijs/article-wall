<?php declare(strict_types=1);

namespace App\Repositories;

use App\Cache;
use App\Models\Article;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use stdClass;

class ArticleRepository
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function all(): array
    {
        try {
            $articlesCollection = [];

            if (!Cache::has('articles')) {
                $response = $this->client->get('https://jsonplaceholder.typicode.com/posts/');
                $responseJson = $response->getBody()->getContents();
                Cache::remember('articles', $responseJson);
            } else {
                $responseJson = Cache::get('articles');
            }

            $articles = json_decode($responseJson);

            foreach ($articles as $article) {
                $articlesCollection[] = $this->buildModel($article);
            }

            return $articlesCollection;

        } catch (GuzzleException $exception) {
            return [];
        }
    }

    public function getById(int $id): ?Article
    {
        try {
            if (!Cache::has('article-' . $id)) {
                $response = $this->client->get('https://jsonplaceholder.typicode.com/posts/' . $id);
                $responseJson = $response->getBody()->getContents();
                Cache::remember('article-' . $id, $responseJson);
            } else {
                $responseJson = Cache::get('article-' . $id);
            }

            return $this->buildModel(json_decode($responseJson));

        } catch (GuzzleException $exception) {
            return null;
        }

    }

    private function buildModel(stdClass $article): Article
    {
        return new Article(
            $article->id,
            $article->userId,
            $article->title,
            $article->body,
            'https://placehold.co/800x400?text=News'
        );
    }

    public function getByUserId(int $userId): array
    {
        try {

            if (!Cache::has('userArticles-' . $userId)) {
                $response = $this->client->get('https://jsonplaceholder.typicode.com/posts?userId=' . $userId);
                $responseJson = $response->getBody()->getContents();
                Cache::remember('userArticles-' . $userId, $responseJson);
            } else {
                $responseJson = Cache::get('userArticles-' . $userId);
            }

            $articleData = json_decode($responseJson);

            $articlesCollection = [];

            foreach ($articleData as $article) {

                $articlesCollection[] = $this->buildModel($article);
            }

            return $articlesCollection;

        } catch (GuzzleException $exception) {
            return [];
        }
    }
}