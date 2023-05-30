<?php declare(strict_types=1);

namespace App\Controllers;

use App\Core\TwigView;
use App\Exceptions\ResourceNotFoundException;
use App\Services\Article\Create\CreateArticleRequest;
use App\Services\Article\Create\CreateArticleService;
use App\Services\Article\DeleteArticleService;
use App\Services\Article\IndexArticleService;
use App\Services\Article\Show\ShowArticleRequest;
use App\Services\Article\Show\ShowArticleService;
use App\Services\Article\Update\UpdateArticleRequest;
use App\Services\Article\Update\UpdateArticleService;

class ArticleController
{
    private IndexArticleService $indexArticleService;
    private CreateArticleService $createArticleService;
    private UpdateArticleService $updateArticleService;
    private DeleteArticleService $deleteArticleService;
    private ShowArticleService $showArticleService;

    public function __construct
    (
        IndexArticleService $indexArticleService,
        ShowArticleService $showArticleService,
        CreateArticleService $createArticleService,
        UpdateArticleService $updateArticleService,
        DeleteArticleService $deleteArticleService
    ) {
        $this->indexArticleService = $indexArticleService;
        $this->createArticleService = $createArticleService;
        $this->deleteArticleService = $deleteArticleService;
        $this->showArticleService = $showArticleService;
        $this->updateArticleService = $updateArticleService;
    }

    public function index(): TwigView
    {
        $articles = $this->indexArticleService->execute();

        return new TwigView('articles', ['articles' => $articles]);
    }

    public function show(array $vars): ?TwigView
    {
        try {
            $articleId = $vars['id'] ?? null;
            $response = $this->showArticleService->execute(new ShowArticleRequest((int)$articleId));

            return new TwigView('singleArticle', [
                'article' => $response->getArticle(),
                'comments' => $response->getComments(),
            ]);
        } catch (ResourceNotFoundException $exception) {
            return null; //  add TwigView not found page
        }
    }

    public function create(): TwigView
    {
        return new TwigView('createArticle', []);
    }

    public function store()
    {
            $createArticleResponse = $this->createArticleService->execute(
                new CreateArticleRequest(
                    $_POST['title'],
                    $_POST['content']
                )
            );

            $article = $createArticleResponse->getArticle();

            header('Location: /articles/' . $article->getId());
    }

    public function edit(array $vars): TwigView
    {
        $response = $this->showArticleService->execute(
            new ShowArticleRequest((int) $vars['id'])
        );

        return new TwigView('editArticle', [
            'article' => $response->getArticle()
        ]);
    }

    public function update(array $vars)
    {

            $response = $this->updateArticleService->execute(
                new UpdateArticleRequest(
                    (int) $vars['id'],
                    $_POST['title'],
                    $_POST['content']
                )
            );

            $article = $response->getArticle();

            header('Location: /articles/' . $article->getId()) . '/edit';

    }

    public function delete(): TwigView
    {
        $articleId = (int)$_POST['articleId'];
        $this->deleteArticleService->execute($articleId);

        return $this->index();
    }
}