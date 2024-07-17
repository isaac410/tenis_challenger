<?php

namespace App\Service;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

use App\Dto\TournamentFaseDto;
use App\Entity\TournamentFase;
use App\Form\TournamentFaseType;
use App\Repository\TournamentFaseRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class TournamentFaseService extends AbstractService {
  private $repository;
  private $formFactory;

  public function __construct(
    ValidatorInterface $validator,
    NormalizerInterface $normalize,
    SerializerInterface $serializer,
    FormFactoryInterface $formFactory,
    TournamentFaseRepository $repository,
) {
    parent::__construct($serializer, $validator, $normalize);
    $this->repository = $repository;
    $this->formFactory = $formFactory;
}

  public function getAndValidEntity(array $data, AbstractType $type, array $groups, int $id = null): TournamentFase {
    $tournamentFase = new TournamentFase();
    if($id) $tournamentFase = $this->repository->findById($id);
    if(!$tournamentFase) throw new HttpException(Response::HTTP_NOT_FOUND, "tournament fase with ID $id, not found");
    $dataNormalize = array_merge($this->normalize($tournamentFase), $data);
    $dataNormalize['tournament'] = $dataNormalize['tournament']['id'];
    $form = $this->formFactory->create($type::class, $tournamentFase, ['validation_groups' => $groups]);
    $form->submit($dataNormalize);
    if (!$form->isValid()) $this->handleFormErrors($form);
    $this->validateEntity($tournamentFase);
    return $tournamentFase;
  }

  public function create(array $data): TournamentFaseDto {
    $tournamentFase = $this->getAndValidEntity($data, new TournamentFaseType(), ['user']);
    $tournamentFase = $this->repository->create($tournamentFase);
    return $this->filterPropeties($tournamentFase, TournamentFaseDto::class, ['user']);
  }

  public function list(): array {
    $tournamentFases = $this->repository->list();
    foreach ($tournamentFases as &$tournamentFase) {
      $tournamentFase = $this->filterPropeties($tournamentFase, TournamentFaseDto::class, ['user']);
    }
    return $tournamentFases;
  }

  public function findById(int $id): TournamentFaseDto {
    $tournamentFase = $this->repository->findById($id);
    if(!$tournamentFase) throw new HttpException(Response::HTTP_NOT_FOUND, "tournament fase with ID $id, not found");
    return $this->filterPropeties($tournamentFase, TournamentFaseDto::class, ['user']);
  }

  public function updateById(int $id, array $data): TournamentFaseDto {
    $tournamentFase = $this->getAndValidEntity($data, new TournamentFaseType(), ['user'], $id);
    $this->repository->update($tournamentFase);
    return $this->filterPropeties($tournamentFase, TournamentFaseDto::class, ['user']);;
  }

  public function deleteById(int $id): void {
    $tournamentFase = $this->repository->findById($id);
    if (!$tournamentFase) throw new HttpException(Response::HTTP_NOT_FOUND, "tournament fase with ID $id, not found");
    $this->repository->delete($tournamentFase);
  }

}