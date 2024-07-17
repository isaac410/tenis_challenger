<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use App\Entity\TournamentFase;
use App\Repository\MatchGameRepository;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: MatchGameRepository::class)]
class MatchGame {

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(targetEntity: Player::class)]
    private Player $playerA;

    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(targetEntity: Player::class)]
    private Player $playerB;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank]
    private int $winner;

    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(targetEntity: TournamentFase::class, inversedBy: 'matchGames')]
    private TournamentFase $tournamentFase;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $updatedAt;

    public function getId(): ?int {
      return $this->id;
    }

    public function getPlayerA(): ?Player {
      return $this->playerA;
    }

    public function setPlayerA(?Player $playerA): self {
      $this->playerA = $playerA;
      return $this;
    }

    public function getPlayerB(): ?Player {
      return $this->playerB;
    }

    public function setPlayerB(?Player $playerB): self {
      $this->playerB = $playerB;
      return $this;
    }

    public function getWinner(): ?int {
      return $this->winner;
    }

    public function setWinner(?int $winner): self {
      $this->winner = $winner;
      return $this;
    }

    public function getTournamentFase(): ?TournamentFase {
      return $this->tournamentFase;
    }

    public function setTournamentFase(?TournamentFase $tournamentFase): self {
      $this->tournamentFase = $tournamentFase;
      return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable {
      return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function setCreatedAt(): void {
      $this->createdAt = new \DateTimeImmutable();
    }

    public function getUpdatedAt(): \DateTimeImmutable {
      return $this->updatedAt;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function setUpdatedAt(): void {
      $this->updatedAt = new \DateTimeImmutable();
    }

    #[Assert\Callback]
    public function validatePlayers(): void {
      if ($this->playerA === $this->playerB) throw new \InvalidArgumentException("players cannot be the same.");
      if ($this->winner !== $this->playerA->getId() && $this->winner !== $this->playerB->getId()) throw new \InvalidArgumentException("Winner must be the same as player A or B.");
    }
}
