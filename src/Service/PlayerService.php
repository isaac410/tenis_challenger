<?php

namespace App\Service;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

use App\Dto\PlayerDto;
use App\Entity\Player;
use App\Form\PlayerType;
use App\Repository\PlayerRepository;

class PlayerService extends AbstractService {
  private $repository;
  private $formFactory;

  public function __construct(
    PlayerRepository $repository,
    ValidatorInterface $validator,
    NormalizerInterface $normalize,
    SerializerInterface $serializer,
    FormFactoryInterface $formFactory,
) {
  $this->repository = $repository;
  $this->formFactory = $formFactory;
  parent::__construct($serializer, $validator, $normalize);
}

  public function getAndValidEntity(array $data, AbstractType $type, array $groups, int $id = null): Player {
    $player = new Player();
    if($id) $player = $this->repository->findById($id);
    if(!$player) throw new HttpException(Response::HTTP_NOT_FOUND, "player with ID $id, not found");
    $dataNormalize = array_merge($this->normalize($player), $data);
    $form = $this->formFactory->create($type::class, $player, ['validation_groups' => $groups]);
    $form->submit($dataNormalize);
    if (!$form->isValid()) $this->handleFormErrors($form);
    $this->validateEntity($player);
    return $player;
  }

  public function create(array $data): PlayerDto {
    $player = $this->getAndValidEntity($data, new PlayerType(), ['user']);
    $player = $this->repository->create($player);
    return $this->filterPropeties($player, PlayerDto::class, ['user']);
  }

  public function list(): array {
    $players = $this->repository->list();
    foreach ($players as &$player) {
      $player = $this->filterPropeties($player, PlayerDto::class, ['user']);
    }
    return $players;
  }

  public function findById(int $id): PlayerDto {
    $player = $this->repository->findById($id);
    if(!$player) throw new HttpException(Response::HTTP_NOT_FOUND, "player with ID $id, not found");
    return $this->filterPropeties($player, PlayerDto::class, ['user']);
  }

  public function updateById(int $id, array $data): PlayerDto {
    $player = $this->getAndValidEntity($data, new PlayerType(), ['user'], $id);
    $this->repository->update($player);
    return $this->filterPropeties($player, PlayerDto::class, ['user']);;
  }

  public function deleteById(int $id): void {
    $player = $this->repository->findById($id);
    if (!$player) throw new HttpException(Response::HTTP_NOT_FOUND, "player with ID $id, not found");
    $this->repository->delete($player);
  }
}