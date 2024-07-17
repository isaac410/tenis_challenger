<?php

namespace App\Dto;

use Symfony\Component\Serializer\Annotation\Groups;

class MatchGameDto {

    #[Groups(["user", "admin"])]
    public int $id;

    #[Groups(["user", "admin"])]
    public PlayerDto $playerA;

    #[Groups(["user", "admin"])]
    public PlayerDto $playerB;

    #[Groups(["user", "admin"])]
    public int $winner = 1;

    #[Groups(["user", "admin"])]
    public TournamentFaseDto $tournamentFase;

    #[Groups(["user", "admin"])]
    public string $createdAt;

    #[Groups(["admin"])]
    public string $updatedAt;
}