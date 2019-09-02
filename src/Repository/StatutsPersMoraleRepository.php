<?php

namespace App\Repository;

use App\Entity\StatutsPersMorale;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method StatutsPersMorale|null find($id, $lockMode = null, $lockVersion = null)
 * @method StatutsPersMorale|null findOneBy(array $criteria, array $orderBy = null)
 * @method StatutsPersMorale[]    findAll()
 * @method StatutsPersMorale[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatutsPersMoraleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StatutsPersMorale::class);
    }

    // /**
    //  * @return StatutsPersMorale[] Returns an array of StatutsPersMorale objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?StatutsPersMorale
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
