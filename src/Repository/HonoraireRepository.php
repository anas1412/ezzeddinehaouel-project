<?php

namespace App\Repository;

use App\Entity\Honoraire;
use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @extends ServiceEntityRepository<Honoraire>
 *
 * @method Honoraire|null find($id, $lockMode = null, $lockVersion = null)
 * @method Honoraire|null findOneBy(array $criteria, array $orderBy = null)
 * @method Honoraire[]    findAll()
 * @method Honoraire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HonoraireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Honoraire::class);
    }

    public function countHonorairesByClient(Client $client): int
    {
        return $this->createQueryBuilder('h')
            ->select('COUNT(h.id)')
            ->andWhere('h.client = :client')
            ->setParameter('client', $client)
            ->getQuery()
            ->getSingleScalarResult();
    }

    //    /**
    //     * @return Honoraire[] Returns an array of Honoraire objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('h')
    //            ->andWhere('h.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('h.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Honoraire
    //    {
    //        return $this->createQueryBuilder('h')
    //            ->andWhere('h.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}