<?php declare(strict_types=1);

namespace App\Controllers;

use App\Core\Redirect;
use App\Core\TwigView;
use App\Exceptions\ResourceNotFoundException;
use App\Services\Article\Create\CreateArticleRequest;
use App\Services\Article\Create\CreateArticleService;
use App\Services\Article\Delete\DeleteArticleService;
use App\Services\Article\Index\IndexArticleService;
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
        try {
            $articles = $this->indexArticleService->execute();

            return new TwigView('articles', ['articles' => $articles]);
        } catch (ResourceNotFoundException $exception) {
            return new TwigView('notFound', []);
        }

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
            return new TwigView('notFound', []);
        }
    }

    public function create(): TwigView
    {
        $isLoggedIn = $_SESSION['authId'] ?? null;

        if ($isLoggedIn) {
            return new TwigView('createArticle', []);
        } else {
            return new TwigView('notAuthorised', []);
        }


    }

    public function store(): Redirect
    {
        $createArticleResponse = $this->createArticleService->execute(
            new CreateArticleRequest(
                $_POST['title'],
                $_POST['content'],
                'https://picsum.photos/id/' . rand(1, 300) . '/800/400',
            )
        );

        $article = $createArticleResponse->getArticle();

        return new Redirect('/articles/' . $article->getId());
    }

    public function edit(array $vars): TwigView
    {
        $isLoggedIn = $_SESSION['authId'] ?? null;

        if ($isLoggedIn) {
            $response = $this->showArticleService->execute(
                new ShowArticleRequest((int)$vars['id'])
            );

            return new TwigView('editArticle', [
                'article' => $response->getArticle(),
            ]);
        } else {
            return new TwigView('notAuthorised', []);
        }


    }

    public function update(array $vars): Redirect
    {

        $response = $this->updateArticleService->execute(
            new UpdateArticleRequest(
                (int)$vars['id'],
                $_POST['title'],
                $_POST['content']
            )
        );

        $article = $response->getArticle();

        return new Redirect(header('/articles/') . $article->getId());

    }

    public function delete(): TwigView
    {

        $articleId = (int)$_POST['articleId'];
        $this->deleteArticleService->execute($articleId);

        return $this->index();
    }
}