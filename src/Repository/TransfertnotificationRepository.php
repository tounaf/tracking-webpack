<?php

namespace App\Repository;

use App\Entity\Transfertnotification;
use App\Entity\FosUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Transfertnotification|null find($id, $lockMode = null, $lockVersion = null)
 * @method Transfertnotification|null findOneBy(array $criteria, array $orderBy = null)
 * @method Transfertnotification[]    findAll()
 * @method Transfertnotification[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransfertnotificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transfertnotification::class);
    }



    // /**
    //  * @return Transfertnotification[] Returns an array of Transfertnotification objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Transfertnotification
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
