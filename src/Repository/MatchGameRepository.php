<?php

namespace App\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

use App\Entity\MatchGame;

/**
 * @extends ServiceEntityRepository<MatchGame>
 */
class MatchGameRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, MatchGame::class);
    }

    /**
     * @return MatchGame[]
     */
    public function list(): array {
        return parent::findAll();
    }

    public function findById(int $id): ?MatchGame {
        return parent::findOneBy(["id" => $id]);
    }

    /**
     * @return MatchGame[]
     */
    public function findByProperty(string $name, mixed $value): ?array {
        return parent::findBy([$name => $value]);
    }

    public function update(MatchGame $matchGame): void {
        $em = $this->getEntityManager();
        $em->persist($matchGame);
        $em->flush();
    }

    public function create(MatchGame $matchGame): MatchGame {
        $em = $this->getEntityManager();
        $em->persist($matchGame);
        $em->flush();
        return $matchGame;
    }

    public function delete(MatchGame $matchGame): void {
        $em = $this->getEntityManager();
        $em->remove($matchGame);
        $em->flush();
    }
}
