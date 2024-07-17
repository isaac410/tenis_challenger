<?php

namespace App\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

use App\Entity\TournamentFase;

/**
 * @extends ServiceEntityRepository<TournamentFase>
 */
class TournamentFaseRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, TournamentFase::class);
    }

    /**
     * @return TournamentFase[]
     */
    public function list(): array {
        return parent::findAll();
    }

    public function findById(int $id): ?TournamentFase {
        return parent::findOneBy(["id" => $id]);
    }

    /**
     * @return TournamentFase[]
     */
    public function findByProperty(string $name, mixed $value): array {
        return parent::findBy([$name => $value]);
    }

    public function update(TournamentFase $tournamentFase): void {
        $em = $this->getEntityManager();
        $em->persist($tournamentFase);
        $em->flush();
    }

    public function create(TournamentFase $tournamentFase): TournamentFase {
        $em = $this->getEntityManager();
        $em->persist($tournamentFase);
        $em->flush();
        return $tournamentFase;
    }

    public function delete(TournamentFase $tournamentFase): void {
        $em = $this->getEntityManager();
        $em->remove($tournamentFase);
        $em->flush();
    }
}
