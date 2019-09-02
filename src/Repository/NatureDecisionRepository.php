<?php

namespace App\Repository;

use App\Entity\NatureDecision;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method NatureDecision|null find($id, $lockMode = null, $lockVersion = null)
 * @method NatureDecision|null findOneBy(array $criteria, array $orderBy = null)
 * @method NatureDecision[]    findAll()
 * @method NatureDecision[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NatureDecisionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NatureDecision::class);
    }

    // /**
    //  * @return NatureDecision[] Returns an array of NatureDecision objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?NatureDecision
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
