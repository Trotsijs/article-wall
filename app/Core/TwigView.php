<?php declare(strict_types=1);

namespace App\Core;

class TwigView implements Response
{
    private string $path;
    private array $data;

    public function __construct(string $path, array $data)
    {
        $this->path = $path;
        $this->data = $data;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getData(): array
    {
        return $this->data;
    }
}