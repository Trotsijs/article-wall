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
            1,
            $request->getTitle(),
            $request->getContent(),
            'https://placehold.co/800x400?text=ARTICLE'

        );

        $this->articleRepository->save($article);

        return new CreateArticleResponse($article);
    }
}
