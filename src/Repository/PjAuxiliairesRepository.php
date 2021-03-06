<?php

namespace App\Repository;

use App\Entity\PjAuxiliaires;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method PjAuxiliaires|null find($id, $lockMode = null, $lockVersion = null)
 * @method PjAuxiliaires|null findOneBy(array $criteria, array $orderBy = null)
 * @method PjAuxiliaires[]    findAll()
 * @method PjAuxiliaires[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PjAuxiliairesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PjAuxiliaires::class);
    }

    // /**
    //  * @return PjAuxiliaires[] Returns an array of PjAuxiliaires objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PjAuxiliaires
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
