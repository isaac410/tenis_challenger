<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use App\Enum\Gender;
use App\Enum\StatusTournament;
use App\Repository\TournamentRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[UniqueEntity('name', 'the name of the tournament already exists...')]
#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: TournamentRepository::class)]
class Tournament {

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 50, unique: true)]
    private string $name;

    #[Assert\NotNull]
    #[Assert\Type(type: Gender::class)]
    #[ORM\Column(enumType: Gender::class)]
    private Gender $gender;

    #[Assert\Type(type: StatusTournament::class)]
    #[ORM\Column(enumType: StatusTournament::class)]
    private StatusTournament $status = StatusTournament::pendding;

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

    public function getGender(): ?Gender {
        return $this->gender;
    }

    public function setGender(Gender $gender): static {
        $this->gender = $gender;
        return $this;
    }

    public function getStatus(): ?StatusTournament {
        return $this->status;
    }

    public function setStatus(StatusTournament $status): static {
        $this->status = $status;
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
