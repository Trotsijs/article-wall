<?php declare(strict_types=1);

namespace App\Models;

class User
{
    private int $id;
    private string $username;
    private string $name;
    private string $email;
    private string $website;

    public function __construct
    (
        int $id,
        string $username,
        string $name,
        string $email,
        string $website

    ) {
        $this->id = $id;
        $this->username = $username;
        $this->name = $name;
        $this->email = $email;
        $this->website = $website;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getWebsite(): string
    {
        return $this->website;
    }
}