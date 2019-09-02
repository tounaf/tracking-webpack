<?php

namespace App\Repository;

use App\Entity\EtapeSuivante;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method EtapeSuivante|null find($id, $lockMode = null, $lockVersion = null)
 * @method EtapeSuivante|null findOneBy(array $criteria, array $orderBy = null)
 * @method EtapeSuivante[]    findAll()
 * @method EtapeSuivante[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EtapeSuivanteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EtapeSuivante::class);
    }

    // /**
    //  * @return EtapeSuivante[] Returns an array of EtapeSuivante objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EtapeSuivante
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
