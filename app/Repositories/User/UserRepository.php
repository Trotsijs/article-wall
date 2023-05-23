<?php declare(strict_types=1);

namespace App\Repositories\User;

use App\Models\User;

interface UserRepository
{
    public function all(): array;
    public function getById(int $id): ?User;

}