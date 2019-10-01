<?php

namespace App\Repository;

use App\Entity\InformationPj;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method InformationPj|null find($id, $lockMode = null, $lockVersion = null)
 * @method InformationPj|null findOneBy(array $criteria, array $orderBy = null)
 * @method InformationPj[]    findAll()
 * @method InformationPj[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InformationPjRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InformationPj::class);
    }

    public function getDossierByIdInfoPj($idInfoPj){
        $sql = "SELECT  D.id, D.`date_litige`,infoPj.`libelle`,infoPj.`filename` FROM dossier AS D LEFT JOIN dossier_information_pj AS di ON D.id = di.`dossier_id`
                LEFT JOIN information_pj AS infoPj ON infoPj.`id` = di.`information_pj_id`
                WHERE infoPj.`id` =".$idInfoPj;
        $qb = $this->getEntityManager()->getConnection()->prepare($sql);
        $qb->execute();
        $result = $qb->fetchAll();
        return $result;
    }

    /*public function getDataDossierInfoPj($idInfoPj){

    }*/

    // /**
    //  * @return InformationPj[] Returns an array of InformationPj objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?InformationPj
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
