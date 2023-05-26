<?php declare(strict_types=1);

namespace App\Services\Article;

use App\Repositories\Article\PdoArticleRepository;

class CreateArticleService
{
    private PdoArticleRepository $pdoArticleRepository;

    public function __construct(PdoArticleRepository $pdoArticleRepository)
    {
        $this->pdoArticleRepository = $pdoArticleRepository;
    }

    public function execute(string $title, string $content)
    {
        $this->pdoArticleRepository->create($title, $content);
    }
}
