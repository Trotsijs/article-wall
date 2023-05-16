<?php declare(strict_types=1);

namespace App\Models;

class Article
{
    private int $userId;
    private string $author;
    private int $postId;
    private string $title;
    private string $body;

    public function __construct(int $userId, string $author, int $postId, string $title, string $body)
    {
        $this->userId = $userId;
        $this->author= $author;
        $this->postId = $postId;
        $this->title = $title;
        $this->body = $body;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }
    public function getAuthor(): string
    {
        return $this->author;
    }

    public function getPostId(): int
    {
        return $this->postId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getBody(): string
    {
        return $this->body;
    }
}