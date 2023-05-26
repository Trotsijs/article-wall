<?php declare(strict_types=1);

namespace App\Controllers;

use App\Core\TwigView;
use App\Exceptions\ResourceNotFoundException;
use App\Services\Article\CreateArticleService;
use App\Services\Article\DeleteArticleService;
use App\Services\Article\IndexArticleService;
use App\Services\Article\Show\ShowArticleRequest;
use App\Services\Article\Show\ShowArticleService;

class ArticleController
{
    private IndexArticleService $indexArticleService;
    private CreateArticleService $createArticleService;
    private DeleteArticleService $deleteArticleService;

    public function __construct
    (
        IndexArticleService $indexArticleService,
        CreateArticleService $createArticleService,
        DeleteArticleService $deleteArticleService
    ) {
        $this->indexArticleService = $indexArticleService;
        $this->createArticleService = $createArticleService;
        $this->deleteArticleService = $deleteArticleService;
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
            $service = new ShowArticleService();
            $response = $service->execute(new ShowArticleRequest((int)$articleId));

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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'] ?? '';
            $content = $_POST['content'] ?? '';

            $this->createArticleService->execute($title, $content);
        }

        return new TwigView('createArticle', []);
    }

    public function delete(): TwigView
    {
        $articleId = (int) $_POST['articleId'];
        $this->deleteArticleService->execute($articleId);

        return $this->index();
    }
}