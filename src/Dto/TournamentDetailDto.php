<?php
namespace App\Dto;

use DateTimeImmutable;

class PlayerDetailDto {
  public int $id;
  public string $name;
  public string $lastname;
  public string $gender;
  public int $power;
  public int $speed;
  public int $reaction;
}

class MatchDetailDto {
  public PlayerDetailDto $playerA;
  public PlayerDetailDto $playerB;
  public int $winner;
  public DateTimeImmutable $createdAt;
}

class TournamentFaseDetailDto {
  public string $name;
  /**
   * @var MatchDetailDto[]
   */
  public array $matches;
}

class TournamentDetailDto {
  public int $id;
  public string $name;
  public string $gender;
  public string $status;
  public DateTimeImmutable $createdAt;
  public DateTimeImmutable $updatedAt;
  /**
   * @var TournamentFaseDetailDto[]
   */
  public array $fases;
}