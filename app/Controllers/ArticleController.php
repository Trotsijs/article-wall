<?php declare(strict_types=1);

namespace App\Controllers;

use App\Core\TwigView;
use App\Exceptions\ResourceNotFoundException;
use App\Repositories\Article\PdoArticleRepository;
use App\Services\Article\CreateArticleService;
use App\Services\Article\IndexArticleService;
use App\Services\Article\Show\ShowArticleRequest;
use App\Services\Article\Show\ShowArticleService;

class ArticleController
{
    private IndexArticleService $indexArticleService;
    private CreateArticleService $createArticleService;

    public function __construct
    (
        IndexArticleService $indexArticleService,
        CreateArticleService $createArticleService
    )
    {
        $this->indexArticleService = $indexArticleService;
        $this->createArticleService = $createArticleService;
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
}