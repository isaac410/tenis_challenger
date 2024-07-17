<?php

namespace App\Service;

use App\Dto\MatchGameDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

use App\Enum\Gender;
use App\Entity\Player;
use App\Entity\MatchGame;
use App\Entity\Tournament;
use App\Dto\TournamentDto;
use App\Form\TournamentType;
use App\Entity\TournamentFase;
use App\Enum\StatusTournament;
use App\Dto\TournamentDetailDto;
use App\Dto\TournamentFaseDto;
use App\Repository\PlayerRepository;
use App\Repository\MatchGameRepository;
use App\Repository\TournamentRepository;
use App\Repository\TournamentFaseRepository;

class TournamentService extends AbstractService {
  private $repository;
  private $formFactory;
  private $playerRepository;
  private $matchGameRepository;
  private $tournamentFaseRepository;

  public function __construct(
    ValidatorInterface $validator,
    NormalizerInterface $normalize,
    SerializerInterface $serializer,
    TournamentRepository $repository,
    FormFactoryInterface $formFactory,
    PlayerRepository $playerRepository,
    MatchGameRepository $matchGameRepository,
    TournamentFaseRepository $tournamentFaseRepository
) {
  $this->repository = $repository;
  $this->formFactory = $formFactory;
  $this->playerRepository = $playerRepository;
  $this->matchGameRepository = $matchGameRepository;
  $this->tournamentFaseRepository = $tournamentFaseRepository;
  parent::__construct($serializer, $validator, $normalize);
}

  public function getAndValidEntity(array $data, AbstractType $type, array $groups, int $id = null): Tournament {
    $tournament = new Tournament();
    if($id) $tournament = $this->repository->findById($id);
    if(!$tournament) throw new HttpException(Response::HTTP_NOT_FOUND, "tournament with ID $id, not found");
    $form = $this->formFactory->create($type::class, $tournament, ['validation_groups' => $groups]);
    $dataNormalize = array_merge($this->normalize($tournament), $data);
    $form->submit($dataNormalize);
    if (!$form->isValid()) $this->handleFormErrors($form);
    $this->validateEntity($tournament);
    return $tournament;
  }

  public function create(array $data) {
    $tournament = $this->getAndValidEntity($data, new TournamentType(), ['user']);
    $tournament = $this->repository->create($tournament);
    return $this->filterPropeties($tournament, TournamentDto::class, ['user']);
  }

  public function list(?string $startDate, ?string $endDate, ?string $gender): array {
    $tournaments = $this->repository->list($startDate, $endDate, $gender);
    foreach ($tournaments as &$tournament) {
        $tournament = $this->filterPropeties($tournament, TournamentDto::class, ['user']);
    }
    return $tournaments;
  }

  public function findById(int $id): TournamentDetailDto {
    $tournament = $this->repository->findById($id);

    if(!$tournament) throw new HttpException(Response::HTTP_NOT_FOUND, "tournament with ID $id, not found");

    $tournament = $this->normalize($tournament, TournamentDto::class);
    $tournamentFase = $this->tournamentFaseRepository->findByProperty('tournament', $tournament['id']);


    if(count($tournamentFase)){
      $tournamentFase = $this->normalize($tournamentFase, TournamentFaseDto::class);

      foreach ($tournamentFase as &$fase) {
        $maches = $this->matchGameRepository->findByProperty('tournamentFase', $fase['id']);
        if( count($maches) ) {
          $maches = $this->normalize($maches, MatchGameDto::class);
          $fase['matches'] = $maches;
        }
      }
      $tournament['fases'] = $tournamentFase;
    }

    return $this->filterPropeties($tournament, TournamentDetailDto::class);
  }

  public function updateById(int $id, array $data): TournamentDto {
    $tournament = $this->getAndValidEntity($data, new TournamentType(), ['user'], $id);
    $this->repository->update($tournament);
    return $this->filterPropeties($tournament, TournamentDto::class, ['user']);;
  }

  public function deleteById(int $id): void {
    $tournament = $this->repository->findById($id);
    if (!$tournament) throw new HttpException(Response::HTTP_NOT_FOUND, "tournament with ID $id, not found");
    $this->repository->delete($tournament);
  }

  public function simulateTournament(int $tournamentId): TournamentDetailDto {
    $tournament = $this->repository->findById($tournamentId);
    if (!$tournament) {
      throw new HttpException(Response::HTTP_NOT_FOUND, "Tournament with ID $tournamentId not found");
    }

    if ($tournament->getStatus()->value === 'finished') {
      throw new HttpException(Response::HTTP_BAD_REQUEST, "The tournament has already been finished before");
    }

    $players = $this->playerRepository->findBy(['gender' => $tournament->getGender()], null, 8);
    if (count($players) < 8) {
      throw new HttpException(Response::HTTP_BAD_REQUEST, "Not enough players to simulate the tournament");
    }

    shuffle($players);
    $round = 1;

    $rounds = [];
    while (count($players) > 1) {
      $fase = new TournamentFase();
      $fase->setTournament($tournament);
      $fase->setName("Round $round");
      $fase->setCreatedAt(new \DateTimeImmutable());
      $fase->setUpdatedAt(new \DateTimeImmutable());
      $fase = $this->tournamentFaseRepository->create($fase);

      $normalizeRound = $this->normalize($fase);

      $matches = [];
      $nextRoundPlayers = [];
      for ($i = 0; $i < count($players); $i += 2) {
        $match = new MatchGame();
        $match->setPlayerA($players[$i]);
        $match->setPlayerB($players[$i + 1]);
        $match->setTournamentFase($fase);
        $match->setCreatedAt(new \DateTimeImmutable());
        $match->setUpdatedAt(new \DateTimeImmutable());

        $winner = $this->simulateMatch($players[$i], $players[$i + 1], $tournament->getGender());
        $match->setWinner($winner->getId());
        $match = $this->matchGameRepository->create($match);

        $normalizeMatch = $this->normalize($match);
        $matches[] = $normalizeMatch;
        $nextRoundPlayers[] = $winner;
      }

      $normalizeRound["matches"] = $matches;
      $rounds[] = $normalizeRound;
      $players = $nextRoundPlayers;
      $round++;
    }

    $tournament->setStatus(StatusTournament::finished);
    $tournament = $this->repository->create($tournament);
    $tournament = $this->normalize($tournament);
    $tournament['fases'] = $rounds;

    return $this->filterPropeties($tournament, TournamentDetailDto::class);
  }

  private function simulateMatch(Player $playerA, Player $playerB, Gender $gender) {
    do {
      $randomA = rand(1, 10);
      $randomB = rand(1, 10);
      if ($gender === Gender::Male) {
        $scoreA = $randomA * $playerA->getSpeed() * $playerA->getPower();
        $scoreB = $randomB * $playerB->getSpeed() * $playerB->getPower();
      } else {
        $scoreA = $randomA * $playerA->getReaction();
        $scoreB = $randomB * $playerB->getReaction();
      }
    } while ($scoreA === $scoreB);
    return $scoreA > $scoreB ? $playerA : $playerB;
  }
}