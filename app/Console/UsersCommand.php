<?php declare(strict_types=1);

namespace App\Console;

use App\Services\User\Show\ShowUserRequest;
use App\Services\User\Show\ShowUserService;

class UsersCommand
{
    private ShowUserService $showUserService;

    public function __construct()
    {
        $this->showUserService = new ShowUserService();
    }

    public function execute($userId)
    {
        $response = $this->showUserService->execute(new ShowUserRequest($userId));
        echo '[#' . $response->getUser()->getId() . '] ' . $response->getUser()->getUsername();
    }
}