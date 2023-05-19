<?php declare(strict_types=1);

namespace App\Services\User\Show;

use App\Models\User;

class ShowUserResponse
{
    private User $user;
    private array $articles;

    public function __construct(User $user, array $articles)
    {

        $this->user = $user;
        $this->articles = $articles;
    }

    public function getArticles(): array
    {
        return $this->articles;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}