<?php

namespace App\Repository;

use App\Entity\Goldbook;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Goldbook|null find($id, $lockMode = null, $lockVersion = null)
 * @method Goldbook|null findOneBy(array $criteria, array $orderBy = null)
 * @method Goldbook[]    findAll()
 * @method Goldbook[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GoldbookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Goldbook::class);
    }

    // /**
    //  * @return Goldbook[] Returns an array of Goldbook objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Goldbook
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
