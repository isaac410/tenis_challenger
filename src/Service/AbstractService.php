<?php

namespace App\Service;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

abstract class AbstractService {

  protected ValidatorInterface $validator;
  protected NormalizerInterface $normalize;
  protected SerializerInterface $serializer;

  public function __construct(SerializerInterface $serializer, ValidatorInterface $validator, NormalizerInterface $normalize) {
    $this->validator = $validator;
    $this->normalize = $normalize;
    $this->serializer = $serializer;
  }

  abstract public function getAndValidEntity(array $data, AbstractType $type, array $array, int $id): object;

  protected function handleFormErrors($form) {
    $formErrors = $form->getErrors(true, true);
    $errorsArray = [];
    foreach ($formErrors as $error) {
      $errorsArray[$error->getOrigin()->getName()][] = $error->getMessage();
    }
    throw new HttpException(Response::HTTP_BAD_REQUEST, json_encode(['errors' => $errorsArray]));
  }

  protected function validateEntity(mixed $entity): void {
    $errors = $this->validator->validate($entity);
    if (count($errors) > 0) throw new HttpException(
      Response::HTTP_CONFLICT, $errors[0]->getMessage()
    );
  }

  protected function normalize($entity): array {
    return $this->normalize->normalize($entity);
  }

  protected function filterPropeties(mixed $entity, string $dto, array $groups = null): object {
    $serializedData = $this->serializer->serialize($entity, 'json');
    if($groups) return $this->serializer->deserialize($serializedData, $dto, 'json', ['groups' => $groups]);
    return $this->serializer->deserialize($serializedData, $dto, 'json');
  }
}