<?php declare(strict_types=1);

namespace App;

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ApiClient
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function fetchArticles(): array
    {
        try {
            $postCollection = [];

            if (!Cache::has('articles')) {
                $response = $this->client->get('https://jsonplaceholder.typicode.com/posts/');
                $responseJson = $response->getBody()->getContents();
                Cache::remember('articles', $responseJson);
            } else {
                $responseJson = Cache::get('articles');
            }

            $posts = json_decode($responseJson);

            foreach ($posts as $post) {
                $author = $this->fetchUserById($post->userId);
                $postCollection[] = new Article
                (
                    $post->userId,
                    $author->getUsername(),
                    $post->id,
                    $post->title,
                    $post->body
                );
            }

            return $postCollection;

        } catch (GuzzleException $exception) {
            return [];
        }
    }

    public function fetchSingleArticle(int $id): ?Article
    {

        try {

            if (!Cache::has('article' . $id)) {
                $response = $this->client->get('https://jsonplaceholder.typicode.com/posts/' . $id);
                $responseJson = $response->getBody()->getContents();
                Cache::remember('article' . $id, $responseJson);
            } else {
                $responseJson = Cache::get('article' . $id);
            }

            $user = json_decode($responseJson);

            $author = $this->fetchUserById($user->userId);

            return new Article
            (
                $user->userId,
                $author->getUsername(),
                $user->id,
                $user->title,
                $user->body
            );

        } catch (GuzzleException $exception) {
            return null;
        }

    }

    public function fetchUsers(): array
    {
        try {

            $userCollection = [];

            if (!Cache::has('users')) {
                $response = $this->client->get('https://jsonplaceholder.typicode.com/users');
                $responseJson = $response->getBody()->getContents();
                Cache::remember('users', $responseJson);
            } else {
                $responseJson = Cache::get('users');
            }

            $userData = json_decode($responseJson);

            foreach ($userData as $user) {
                $userCollection[] = new User
                (
                    $user->id,
                    $user->username,
                    $user->name,
                    $user->email,
                    $user->address->city
                );
            }

            return $userCollection;

        } catch (GuzzleException $exception) {
            return [];
        }
    }

    public function fetchUserById($id): ?User
    {
        try {

            if (!Cache::has('user' . $id)) {
                $response = $this->client->get('https://jsonplaceholder.typicode.com/users/' . $id);
                $responseJson = $response->getBody()->getContents();
                Cache::remember('user' . $id, $responseJson);
            } else {
                $responseJson = Cache::get('user' . $id);
            }

            $userData = json_decode($responseJson);

            return new User
            (
                $userData->id,
                $userData->username,
                $userData->name,
                $userData->email,
                $userData->website
            );

        } catch (GuzzleException $exception) {
            return null;
        }
    }

    public function fetchComments(int $id): array
    {
        try {

            if (!Cache::has('comments' . $id)) {
                $response = $this->client->get('https://jsonplaceholder.typicode.com/comments?postId=' . $id);
                $responseJson = $response->getBody()->getContents();
                Cache::remember('comments' . $id, $responseJson);
            } else {
                $responseJson = Cache::get('comments' . $id);
            }

            $commentData = json_decode($responseJson);

            $commentsCollection = [];

            foreach ($commentData as $comment) {
                $commentsCollection[] = new Comment
                (
                    $comment->postId,
                    $comment->id,
                    $comment->name,
                    $comment->email,
                    $comment->body
                );
            }

            return $commentsCollection;

        } catch (GuzzleException $exception) {
            return [];
        }
    }

    public function fetchArticlesByUser(int $id): array
    {
        try {

            if (!Cache::has('userArticles' . $id)) {
                $response = $this->client->get('https://jsonplaceholder.typicode.com/posts?userId=' . $id);
                $responseJson = $response->getBody()->getContents();
                Cache::remember('userArticles' . $id, $responseJson);
            } else {
                $responseJson = Cache::get('userArticles' . $id);
            }

            $postData = json_decode($responseJson);

            $postCollection = [];

            foreach ($postData as $post) {
                $author = $this->fetchUserById($post->userId);

                $postCollection[] = new Article
                (
                    $post->userId,
                    $author->getUsername(),
                    $post->id,
                    $post->title,
                    $post->body
                );
            }

            return $postCollection;

        } catch (GuzzleException $exception) {
            return [];
        }
    }
}