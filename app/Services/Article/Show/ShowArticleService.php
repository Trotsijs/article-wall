<?php declare(strict_types=1);

namespace App\Services\Article\Show;

use App\Exceptions\ResourceNotFoundException;
use App\Repositories\Article\ArticleRepository;
use App\Repositories\Article\JsonPlaceholderArticleRepository;
use App\Repositories\Comment\CommentRepository;
use App\Repositories\Comment\JsonPlaceholderCommentRepository;
use App\Repositories\User\JsonPlaceholderUserRepository;
use App\Repositories\User\UserRepository;


class ShowArticleService
{
    private ArticleRepository $articleRepository;
    private UserRepository $userRepository;
    private CommentRepository $commentRepository;

    public function __construct()
    {
        $this->articleRepository = new JsonPlaceholderArticleRepository();
        $this->userRepository = new JsonPlaceholderUserRepository();
        $this->commentRepository = new JsonPlaceholderCommentRepository();
    }

    public function execute(ShowArticleRequest $request): ShowArticleResponse
    {
        $article = $this->articleRepository->getById($request->getArticleId());
        if ($article == null) {
            throw new ResourceNotFoundException('Article' . $request->getArticleId() . 'not found.');
        }

        $author = $this->userRepository->getById($article->getAuthorId());

        if ($author == null) {
            throw new ResourceNotFoundException('Author ' . $article->getAuthorId() . 'not found.');
        }

        $article->setAuthor($author);

        $comments = $this->commentRepository->getByArticleId($article->getId());

        return new ShowArticleResponse($article, $comments);
    }
}