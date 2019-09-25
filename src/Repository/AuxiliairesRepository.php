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
    /**
     * @var array
     */
    private $column = array(
        'id',
        'nomPrenom',
        'convenu',
        'payer',
        'reste_payer',
        'devise',
        'prestation',
        'statuts'
    );
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Auxiliaires::class);
    }

     public function getListAuxiliairesActuel( $extraParams,$idDossier, $count = false)
    {
        $sql = $count?' SELECT COUNT(d.`id`) AS record ' : 'SELECT 
                    a.`id`,
                    a.`nom_prenom` AS nomPrenom,
                    a.`convenu` AS convenu ,
                    a.`payer` AS payer,
                    a.`reste_payer` AS reste_payer,
                    a.`statut_intervenant` AS statuts,
                    d.`code` AS devise,
                    p.`libelle` AS prestation
               ';
        $sql .= '  FROM auxiliaires a ';
        $sql .=' INNER JOIN `devise` AS d ON d.`id` = a.`devise_id` ';
        $sql .=' INNER JOIN type_prestation p ON p.`id` = a.`prestation_id` ';
        $sql .=' WHERE a.dossier_id = '.$idDossier;
        $sql .=' GROUP BY prestation_id';
        if (!$count) {
            if (isset($this->column[$extraParams['orderBy']]) && isset($extraParams['order'])) {

                $sql .= ' ORDER BY '.$this->column[$extraParams['orderBy']] .' '. $extraParams['order'];
            }
            if (isset($extraParams['start']) && isset($extraParams['length'])) {

                $sql .= ' LIMIT '.$extraParams['start'].','. $extraParams['length'];
            }
        }
        $qb = $this->getEntityManager()->getConnection()->prepare($sql);
        $qb->execute();
        $result = $qb->fetchAll();
        return $result;
    }


    public function getListAuxiliaires( $extraParams,$idDossier, $count = false)
    {
        $sql = $count?' SELECT COUNT(d.`id`) AS record ' : 'SELECT 
                    a.`id`,
                    a.`nom_prenom` AS nomPrenom,
                    a.`convenu` AS convenu ,
                    a.`payer` AS payer,
                    a.`reste_payer` AS reste_payer,
                    a.`statut_intervenant` AS statuts,
                    d.`code` AS devise,
                    p.`libelle` AS prestation
               ';
        $sql .= '  FROM auxiliaires a ';
        $sql .=' INNER JOIN `devise` AS d ON d.`id` = a.`devise_id` ';
        $sql .=' INNER JOIN type_prestation p ON p.`id` = a.`prestation_id` ';
        $sql .=' WHERE a.dossier_id = '.$idDossier;
        if (!$count) {
            if (isset($this->column[$extraParams['orderBy']]) && isset($extraParams['order'])) {

                $sql .= ' ORDER BY '.$this->column[$extraParams['orderBy']] .' '. $extraParams['order'];
            }
            if (isset($extraParams['start']) && isset($extraParams['length'])) {

                $sql .= ' LIMIT '.$extraParams['start'].','. $extraParams['length'];
            }
        }
        $qb = $this->getEntityManager()->getConnection()->prepare($sql);
        $qb->execute();
        $result = $qb->fetchAll();
        return $result;
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
