<?php

namespace App\Model;

use DateTime;

class Poke
{
    public function __construct(
        private int $id,
        private int|User $pokedByUser,
        private int|User $pokedUser,
        private DateTime $pokedAt,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPokedByUser(): int|User
    {
        return $this->pokedByUser;
    }


    public function getPokedUser(): int|User
    {
        return $this->pokedUser;
    }

    public function getPokedAt(): DateTime
    {
        return $this->pokedAt;
    }
}
