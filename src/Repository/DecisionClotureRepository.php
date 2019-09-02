<?php

namespace App\Repository;

use App\Entity\DecisionCloture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method DecisionCloture|null find($id, $lockMode = null, $lockVersion = null)
 * @method DecisionCloture|null findOneBy(array $criteria, array $orderBy = null)
 * @method DecisionCloture[]    findAll()
 * @method DecisionCloture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DecisionClotureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DecisionCloture::class);
    }

    // /**
    //  * @return DecisionCloture[] Returns an array of DecisionCloture objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DecisionCloture
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
