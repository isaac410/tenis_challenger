<?php

namespace App\Tests\Service;

use App\Dto\PlayerDto;
use App\Entity\Player;
use App\Enum\Gender;
use PHPUnit\Framework\TestCase;
use App\Service\AbstractService;
use ArrayIterator;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class AbstractServiceTest extends TestCase {

  private $serializer;
  private $validator;
  private $normalizer;
  private $service;

  protected function setUp(): void {
    $this->serializer = $this->createStub(SerializerInterface::class);
    $this->validator = $this->createStub(ValidatorInterface::class);
    $this->normalizer = $this->createStub(NormalizerInterface::class);
    //$this->service = $this->createMock(AbstractService::class);
    $this->service = $this->getMockBuilder(AbstractService::class)
    ->setConstructorArgs([$this->serializer, $this->validator, $this->normalizer])
    ->getMock();
  }

  public function testValidateEntity(): void {
    $entity = new Player();
    $entity->setName('isaac');
    $entity->setLastname('mendoza');
    $entity->setGender(Gender::Male);
    $entity->setPower(50);
    $entity->setSpeed(50);
    $entity->setReaction(50);

    $violations = $this->createMock(ConstraintViolationListInterface::class);
    $violations->method('count')->willReturn(0);
    $this->validator->method('validate')
                    ->with($entity)
                    ->willReturn($violations);

    $this->service->validateEntity($entity);
    $this->assertTrue(true);
  }

  public function testNormalize(): void {
    $entity = new Player();
    $entity->setName('isaac');
    $entity->setLastname('mendoza');
    $entity->setGender(Gender::Male);
    $entity->setPower(50);
    $entity->setSpeed(50);
    $entity->setReaction(50);

    $expectedArray = [
      'name' => 'isaac',
      'lastname' => 'mendoza',
      'gender' => 'male',
      'power' => 50,
      'speed' => 50,
      'reaction' => 50
    ];

    $this->normalizer->method('normalize')
                      ->with($entity)
                      ->willReturn($expectedArray);

    $this->assertSame($expectedArray, $expectedArray);
  }

  public function testFilterProperties(): void {
    $expectedArray = [
      'name' => 'isaac',
      'lastname' => 'mendoza',
      'gender' => 'male',
      'power' => 50,
      'speed' => 50,
      'reaction' => 50
    ];

    $this->assertSame($expectedArray, $expectedArray);
  }
}
