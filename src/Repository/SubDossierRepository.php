<?php

namespace App\Repository;

use App\Entity\SubDossier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method SubDossier|null find($id, $lockMode = null, $lockVersion = null)
 * @method SubDossier|null findOneBy(array $criteria, array $orderBy = null)
 * @method SubDossier[]    findAll()
 * @method SubDossier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubDossierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SubDossier::class);
    }

    // /**
    //  * @return SubDossier[] Returns an array of SubDossier objects
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
    public function findOneBySomeField($value): ?SubDossier
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
