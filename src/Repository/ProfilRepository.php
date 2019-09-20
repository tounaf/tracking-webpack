<?php

namespace App\Repository;

use App\Entity\Profil;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Profil|null find($id, $lockMode = null, $lockVersion = null)
 * @method Profil|null findOneBy(array $criteria, array $orderBy = null)
 * @method Profil[]    findAll()
 * @method Profil[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProfilRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Profil::class);
    }

    /**
     * @param $isSuper
     * @param $isAdmin
     * @param $isJuriste
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getProfileByAdmin($isSuper, $isAdmin, $isJuriste)
    {
        $query = $this->createQueryBuilder('p');
//        $query
            //->innerJoin('f.profil', 'p')
//            ->andWhere('p.enable = true');
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
    //  * @return Profil[] Returns an array of Profil objects
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
    public function findOneBySomeField($value): ?Profil
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
