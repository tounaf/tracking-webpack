<?php

namespace App\Repository;

use App\Entity\CategorieLitige;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method CategorieLitige|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategorieLitige|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategorieLitige[]    findAll()
 * @method CategorieLitige[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategorieLitigeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategorieLitige::class);
    }

    // /**
    //  * @return CategorieLitige[] Returns an array of CategorieLitige objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CategorieLitige
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
