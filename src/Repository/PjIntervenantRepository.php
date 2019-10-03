<?php

namespace App\Repository;

use App\Entity\PjIntervenant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method PjIntervenant|null find($id, $lockMode = null, $lockVersion = null)
 * @method PjIntervenant|null findOneBy(array $criteria, array $orderBy = null)
 * @method PjIntervenant[]    findAll()
 * @method PjIntervenant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PjIntervenantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PjIntervenant::class);
    }

    // /**
    //  * @return PjIntervenant[] Returns an array of PjIntervenant objects
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
    public function findOneBySomeField($value): ?PjIntervenant
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
