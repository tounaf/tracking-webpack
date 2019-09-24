<?php

namespace App\Repository;

use App\Entity\PartiAdverse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method PartiAdverse|null find($id, $lockMode = null, $lockVersion = null)
 * @method PartiAdverse|null findOneBy(array $criteria, array $orderBy = null)
 * @method PartiAdverse[]    findAll()
 * @method PartiAdverse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PartiAdverseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PartiAdverse::class);
    }

    // /**
    //  * @return PartiAdverse[] Returns an array of PartiAdverse objects
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
    public function findOneBySomeField($value): ?PartiAdverse
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
