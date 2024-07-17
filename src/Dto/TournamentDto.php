<?php

namespace App\Dto;

use App\Enum\Gender;
use App\Enum\StatusTournament;
use Symfony\Component\Serializer\Annotation\Groups;

class TournamentDto {

    #[Groups(["user", "admin"])]
    public int $id;

    #[Groups(["user", "admin"])]
    public string $name;

    #[Groups(["user", "admin"])]
    public Gender $gender;

    #[Groups(["user", "admin"])]
    public StatusTournament $status = StatusTournament::pendding;

    #[Groups(["user", "admin"])]
    public string $createdAt;

    #[Groups(["admin"])]
    public string $updatedAt;
}