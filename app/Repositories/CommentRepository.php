<?php declare(strict_types=1);

namespace App\Repositories;

use App\Cache;
use App\Models\Comment;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use stdClass;

class CommentRepository
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function getByArticleId(int $articleId): array
    {
        try {

            if (!Cache::has('comments-' . $articleId)) {
                $response = $this->client->get('https://jsonplaceholder.typicode.com/comments?postId=' . $articleId);
                $responseJson = $response->getBody()->getContents();
                Cache::remember('comments-' . $articleId, $responseJson);
            } else {
                $responseJson = Cache::get('comments-' . $articleId);
            }

            $commentData = json_decode($responseJson);

            $commentsCollection = [];

            foreach ($commentData as $comment) {
                $commentsCollection[] = $this->buildModel($comment);
            }

            return $commentsCollection;

        } catch (GuzzleException $exception) {
            return [];
        }
    }

    private function buildModel(stdClass $comment): Comment
    {
        return new Comment(
            $comment->postId,
            $comment->id,
            $comment->name,
            $comment->email,
            $comment->body
        );
    }
}