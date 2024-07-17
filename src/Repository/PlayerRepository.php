<?php

namespace App\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

use App\Entity\Player;

/**
 * @extends ServiceEntityRepository<Player>
 */
class PlayerRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Player::class);
    }

    /**
     * @return Player[]
     */
    public function list(): array {
        return parent::findAll();
    }

    public function findById(int $id): ?Player {
        return parent::findOneBy(["id" => $id]);
    }

    public function update(Player $player): void {
        $em = $this->getEntityManager();
        $em->persist($player);
        $em->flush();
    }

    public function create(Player $player): Player {
        $em = $this->getEntityManager();
        $em->persist($player);
        $em->flush();
        return $player;
    }

    public function delete(Player $player): void {
        $em = $this->getEntityManager();
        $em->remove($player);
        $em->flush();
    }

    /**
     * @return Player[] Returns an array of Player objects
     */
    /* public function findByExampleField($value): array {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findOneBySomeField($value): ?Player {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    } */
}
