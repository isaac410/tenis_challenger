<?php

namespace App\Service;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

use App\Dto\MatchGameDto;
use App\Entity\MatchGame;
use App\Form\MatchGameType;
use App\Repository\MatchGameRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class MatchGameService extends AbstractService {
  private $repository;
  private $formFactory;

  public function __construct(
    ValidatorInterface $validator,
    NormalizerInterface $normalize,
    SerializerInterface $serializer,
    MatchGameRepository $repository,
    FormFactoryInterface $formFactory,
) {
    parent::__construct($serializer, $validator, $normalize);
    $this->repository = $repository;
    $this->formFactory = $formFactory;
}

  public function getAndValidEntity(array $data, AbstractType $type, array $groups, int $id = null): MatchGame {
    $matchGame = new MatchGame();
    if($id) $matchGame = $this->repository->findById($id);
    if(!$matchGame) throw new HttpException(Response::HTTP_NOT_FOUND, "match game with ID $id, not found");
    $dataNormalize = array_merge($this->normalize($matchGame), $data);
    $dataNormalize['playerA'] = $dataNormalize['playerA']['id'];
    $dataNormalize['playerB'] = $dataNormalize['playerB']['id'];
    $dataNormalize['tournamentFase'] = $dataNormalize['tournamentFase']['id'];
    $form = $this->formFactory->create($type::class, $matchGame, ['validation_groups' => $groups]);
    $form->submit($dataNormalize);
    if (!$form->isValid()) $this->handleFormErrors($form);
    $this->validateEntity($matchGame);
    return $matchGame;
  }

  public function create(array $data): MatchGameDto {
    $matchGame = $this->getAndValidEntity($data, new MatchGameType(), ['user']);
    $matchGame = $this->repository->create($matchGame);
    return $this->filterPropeties($matchGame, MatchGameDto::class, ['user']);
  }

  public function list(): array {
    $matchGames = $this->repository->list();
    foreach ($matchGames as &$matchGame) {
      $matchGame = $this->filterPropeties($matchGame, MatchGameDto::class, ['user']);
    }
    return $matchGames;
  }

  public function findById(int $id): MatchGameDto {
    $matchGame = $this->repository->findById($id);
    if(!$matchGame) throw new HttpException(Response::HTTP_NOT_FOUND, "match game with ID $id, not found");
    return $this->filterPropeties($matchGame, MatchGameDto::class, ['user']);
  }

  public function updateById(int $id, array $data) {
    $matchGame = $this->getAndValidEntity($data, new MatchGameType(), ['user'], $id);
    $this->repository->update($matchGame);
    return $this->filterPropeties($matchGame, MatchGameDto::class, ['user']);;
  }

  public function deleteById(int $id): void {
    $matchGame = $this->repository->findById($id);
    if (!$matchGame) throw new HttpException(Response::HTTP_NOT_FOUND, "match game with ID $id, not found");
    $this->repository->delete($matchGame);
  }

}