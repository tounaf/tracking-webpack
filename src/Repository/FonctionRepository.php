<?php

namespace App\Repository;

use App\Entity\Fonction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Fonction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fonction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fonction[]    findAll()
 * @method Fonction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FonctionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fonction::class);
    }

    /**
     * @param $isSuper
     * @param $isAdmin
     * @param $isJuriste
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getProfileByAdmin($isSuper, $isAdmin, $isJuriste)
    {
        $query = $this->createQueryBuilder('f');
        $query
            ->innerJoin('f.profil', 'p')
            ->andWhere('f.enable = true');
        if ($isSuper){
            return $query;
        } elseif($isAdmin) {
            $query
                ->andWhere('p.code <> :role_code')
                ->setParameter('role_code','ROLE_SUPERADMIN');
        }
        elseif ($isJuriste) {
            $query
                ->andWhere('p.code = :role_code')
                ->setParameter('role_code','ROLE_JURISTE');
        }

            ;
        return $query;
    }

    // /**
    //  * @return Fonction[] Returns an array of Fonction objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Fonction
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
