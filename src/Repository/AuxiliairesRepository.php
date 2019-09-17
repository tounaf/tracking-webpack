<?php

namespace App\Repository;

use App\Entity\Auxiliaires;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Auxiliaires|null find($id, $lockMode = null, $lockVersion = null)
 * @method Auxiliaires|null findOneBy(array $criteria, array $orderBy = null)
 * @method Auxiliaires[]    findAll()
 * @method Auxiliaires[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuxiliairesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Auxiliaires::class);
    }

    public function ajaxlistAuxiliaire($data, $page = 0, $max = NULL, $getResult = true)
    {
        $qb = $this->_em->createQueryBuilder();
        $query = isset($data['query']) && $data['query']?$data['query']:null;
        $qb
            ->select('u')
            //->addSelect("concat")
            ->from('App:Auxiliaires', 'u')
        ;
        if ($query) {
            $qb
                ->andWhere('u.convenu like :query')
                ->setParameter('query', "%".$query."%")
            ;
        }
        if ($max) {
            $preparedQuery = $qb->getQuery()
                ->setMaxResults($max)
                ->setFirstResult($page * $max)
            ;
        } else {
            $preparedQuery = $qb->getQuery();
        }
        return $getResult?$preparedQuery->getResult():$preparedQuery;
    }
    // /**
    //  * @return Auxiliaires[] Returns an array of Auxiliaires objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Auxiliaires
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
