<?php

namespace App\Model;

class User
{
    public function __construct(
        private int $id,
        private string $username,
        private string $firstName,
        private string $lastName,
        private string $email, 
        private array $pokes = []
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPokes(): array
    {
        return $this->pokes;
    }

    public function setPokes(array $pokes): void
    {
        $this->pokes = $pokes;
    }
}
