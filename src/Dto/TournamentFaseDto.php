<?php

namespace App\Dto;

use Symfony\Component\Serializer\Annotation\Groups;

class TournamentFaseDto {

    #[Groups(["user", "admin"])]
    public int $id;

    #[Groups(["user", "admin"])]
    public string $name;

    #[Groups(["user", "admin"])]
    public TournamentDto $tournament;

    #[Groups(["admin"])]
    public string $createdAt;

    #[Groups(["admin"])]
    public string $updatedAt;
}