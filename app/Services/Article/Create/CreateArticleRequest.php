<?php declare(strict_types=1);

namespace App\Services\Article\Create;

class CreateArticleRequest
{
    private string $title;
    private string $content;
    private string $avatar;

    public function __construct(string $title, string $content, string $avatar)
    {

        $this->title = $title;
        $this->content = $content;
        $this->avatar = $avatar;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getAvatar(): string
    {
        return $this->avatar;
    }
}