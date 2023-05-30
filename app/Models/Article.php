<?php declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;

class Article
{
    private ?int $id;
    private int $authorId;
    private string $title;
    private string $content;
    private string $avatar;
    private ?User $author = null;
    private string $createdAt;


    public function __construct
    (
        int $authorId,
        string $title,
        string $content,
        string $avatar,
        string $createdAt = null,
        ?int $id = null
    ) {
        $this->id = $id;
        $this->authorId = $authorId;
        $this->title = $title;
        $this->content = $content;
        $this->avatar = $avatar;
        $this->createdAt = $createdAt ?? Carbon::now()->format('Y-m-d H:i:s');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getAuthorId(): int
    {
        return $this->authorId;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getAvatar(): string
    {
        return $this->avatar;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(User $author)
    {
        $this->author = $author;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function update(array $attributes): void
    {
        foreach ($attributes as $attribute => $value) {
            $this->{$attribute} = $value;
        }
    }
}