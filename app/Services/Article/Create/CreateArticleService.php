<?php declare(strict_types=1);

namespace App\Services\Article\Create;

use App\Models\Article;
use App\Repositories\Article\ArticleRepository;

class CreateArticleService
{
    private ArticleRepository $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function execute(CreateArticleRequest $request): CreateArticleResponse
    {
        $article = new Article(
            $_SESSION['authId'],
            $request->getTitle(),
            $request->getContent(),
            'https://picsum.photos/id/'. rand(1, 300). '/800/400'

        );

        $this->articleRepository->save($article);

        return new CreateArticleResponse($article);
    }
}
