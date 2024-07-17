<?php

namespace App\Service;

//use ReflectionClass;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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

  /* protected function setArrayValuesToEntity(array $data, object $entity): object {
    foreach ($data as $key => $value) {
      if (property_exists($entity::class, $key)) {
        $setter = 'set' . ucfirst($key);
        if (method_exists($entity, $setter)) {
          $entity->$setter($value);
        } else {
          $entity->$key = $value;
        }
      }
    }
    return $entity;
  }

  protected function transferDtoPropertiesValuesToEntity(object $dto, object $entity): object {
    $properties = get_object_vars($dto);
    foreach ($properties as $key => $value) {
      $setter = 'set' . ucfirst($key);
      if (property_exists($entity, $key)) {
        $entity->$setter($value);
      }
    }
    return $entity;
  } */
}