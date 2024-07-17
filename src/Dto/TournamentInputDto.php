<?php

namespace App\Dto;

use App\Enum\Gender;
use App\Enum\StatusTournament;

class TournamentInputDto {
    public string $name;
    public Gender $gender;
    public StatusTournament $status = StatusTournament::pendding;
}