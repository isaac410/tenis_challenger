<?php

namespace App\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

use App\Entity\Tournament;

/**
 * @extends ServiceEntityRepository<Tournament>
 */
class TournamentRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Tournament::class);
    }

    /**
     * @return Tournament[]
     */
    public function list(?string $startDate, ?string $endDate, ?string $gender): array {
        $qb = $this->createQueryBuilder('t');

        if ($startDate) {
            $qb->andWhere('t.createdAt >= :startDate')->setParameter('startDate', new \DateTimeImmutable($startDate));
        }

        if ($endDate) {
            $endDate = (new \DateTimeImmutable($endDate))->setTime(23, 59, 59);
            $qb->andWhere('t.createdAt <= :endDate')->setParameter('endDate', $endDate);
        }

        if ($gender) {
            $qb->andWhere('t.gender = :gender')->setParameter('gender', $gender);
        }
        return $qb->getQuery()->getResult();
    }

    public function getFullyList(): array {
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
            SELECT t.id, t.name, t.gender, t.status, t.created_at, t.updated_at, tf.id AS fase_id, tf.name AS fase_name
            FROM tournament t
            LEFT JOIN tournament_fase tf ON t.id = tf.tournament_id
        ';
        $stmt = $conn->prepare($sql);
        $results = $stmt->executeQuery();
        $tournaments = [];

        foreach ($results as $row) {
            $tournamentId = $row['id'];
            if (!isset($tournaments[$tournamentId])) {
                $tournaments[$tournamentId] = [
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'gender' => $row['gender'],
                    'status' => $row['status'],
                    'createdAt' => $row['created_at'],
                    'updatedAt' => $row['updated_at'],
                    'fases' => []
                ];
            }

            if ($row['fase_id']) {
                $tournaments[$tournamentId]['fases'][] = [
                    'id' => $row['fase_id'],
                    'name' => $row['fase_name']
                ];
            }
        }

        return array_values($tournaments);
    }

    public function findById(int $id): ?Tournament {
        return parent::findOneBy(["id" => $id]);
    }

    public function update(Tournament $tournament): void {
        $em = $this->getEntityManager();
        $em->persist($tournament);
        $em->flush();
    }

    public function create(Tournament $tournament): Tournament {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($tournament);
        $entityManager->flush();
        return $tournament;
    }

    public function delete(Tournament $tournament): void {
        $entityManager = $this->getEntityManager();
        $entityManager->remove($tournament);
        $entityManager->flush();
    }
}
