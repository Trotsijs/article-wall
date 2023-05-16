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
            $response = $this->client->get('https://jsonplaceholder.typicode.com/posts/');
            $postData = json_decode($response->getBody()->getContents());

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

    public function fetchSingleArticle(int $id): ?Article
    {

        try {
            $response = $this->client->get('https://jsonplaceholder.typicode.com/posts/' . $id);
            $postData = json_decode($response->getBody()->getContents());
            $author = $this->fetchUserById($postData->userId);

            return new Article
            (
                $postData->userId,
                $author->getUsername(),
                $postData->id,
                $postData->title,
                $postData->body
            );

        } catch (GuzzleException $exception) {
            return null;
        }

    }

    public function fetchUsers(): array
    {
        try {
            $response = $this->client->get('https://jsonplaceholder.typicode.com/users');
            $userData = json_decode($response->getBody()->getContents());

            $userCollection = [];

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
            $response = $this->client->get('https://jsonplaceholder.typicode.com/users/' . $id);
            $userData = json_decode($response->getBody()->getContents());

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
            $response = $this->client->get('https://jsonplaceholder.typicode.com/comments?postId=' . $id);
            $commentData = json_decode($response->getBody()->getContents());

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
            $response = $this->client->get('https://jsonplaceholder.typicode.com/posts?userId=' . $id);
            $postData = json_decode($response->getBody()->getContents());

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