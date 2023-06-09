<?php declare(strict_types=1);

namespace App\Models;

class User
{
    private ?int $id;
    private string $name;
    private string $email;
    private string $password;

    public function __construct
    (
        string $name,
        string $email,
        string $password,
        ?int $id = null

    ) {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

}