<?php

namespace App\Entity;

use App\Enum\Gender;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PlayerRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: PlayerRepository::class)]
class Player {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[Assert\NotBlank()]
    #[ORM\Column(type: 'string', length: 20)]
    private string $name;

    #[Assert\NotBlank()]
    #[ORM\Column(type: 'string', length: 20)]
    private string $lastname;

    #[Assert\NotNull]
    #[Assert\Type(type: Gender::class)]
    #[ORM\Column(enumType: Gender::class)]
    private Gender $gender;

    #[Assert\NotBlank()]
    #[Assert\Range(min: 1, max: 100)]
    #[ORM\Column(type: 'integer')]
    private int $power;

    #[Assert\NotBlank()]
    #[Assert\Range(min: 1, max: 100)]
    #[ORM\Column(type: 'integer')]
    private int $speed;

    #[Assert\NotBlank()]
    #[Assert\Range(min: 1, max: 100)]
    #[ORM\Column(type: 'integer', )]
    private int $reaction;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $updatedAt;

    public function getId(): ?int {
        return $this->id;
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function setName(string $name): static {
        $this->name = $name;
        return $this;
    }

    public function getLastname(): ?string {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static {
        $this->lastname = $lastname;
        return $this;
    }

    public function getGender(): Gender {
        return $this->gender;
    }

    public function setGender(Gender $gender): static {
        $this->gender = $gender;
        return $this;
    }

    public function getPower(): ?int {
        return $this->power;
    }

    public function setPower(int $power): self {
        $this->power = $power;
        return $this;
    }

    public function getSpeed(): ?int {
        return $this->speed;
    }

    public function setSpeed(int $speed): self {
        $this->speed = $speed;
        return $this;
    }

    public function getReaction(): ?int {
        return $this->reaction;
    }

    public function setReaction(int $reaction): self {
        $this->reaction = $reaction;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function setCreatedAt(): void {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getUpdatedAt(): ?\DateTimeImmutable {
        return $this->updatedAt;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function setUpdatedAt(): void {
        $this->updatedAt = new \DateTimeImmutable();
    }
}