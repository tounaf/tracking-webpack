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
        $sql = $count?' SELECT COUNT(aux.`id`) AS record ' : 'SELECT 
                    aux.`id`,
                    aux.`nom_prenom` AS nomPrenom,
                    aux.`convenu` AS convenu ,
                    aux.`payer` AS payer,
                    aux.`reste_payer` AS reste_payer,
                    aux.`statut_intervenant` AS statuts,
                   (
                     SELECT d.code FROM devise d WHERE d.id = aux.`devise_auxi_conv_id`
                   ) AS devise,
                    
                    (
                     SELECT p.libelle FROM `type_prestation` p WHERE p.id = aux.prestation_id
                  ) AS prestation,
                   (
                     SELECT pj.filename FROM `pj_auxiliaires` pj WHERE pj.auxiliaire_id = aux.id
                  ) AS filename
               ';
        $sql .= '  FROM auxiliaires aux ';
        $sql .= ' WHERE aux.id IN 
                      (SELECT 
                        MAX(a.id) 
                      FROM
                        `type_prestation` tp 
                        INNER JOIN `auxiliaires` a 
                          ON tp.`id` = a.`prestation_id` 
                      WHERE a.`dossier_id` ='.$idDossier.' 
                      GROUP BY tp.`id`)';

        if (!$count) {
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
        $sql = $count?' SELECT COUNT(aux.`id`) AS record ' : 'SELECT 
                    aux.`id`,
                    aux.`nom_prenom` AS nomPrenom,
                    aux.`convenu` AS convenu ,
                    aux.`payer` AS payer,
                    aux.`reste_payer` AS reste_payer,
                    aux.`statut_intervenant` AS statuts,
                   (
                     SELECT d.code FROM devise d WHERE d.id = aux.`devise_auxi_conv_id`
                   ) AS devise,
                    
                    (
                     SELECT p.libelle FROM `type_prestation` p WHERE p.id = aux.prestation_id
                  ) AS prestation,
                   (
                     SELECT pj.filename FROM `pj_auxiliaires` pj WHERE pj.auxiliaire_id = aux.id
                  ) AS filename
               ';
        $sql .= '  FROM auxiliaires aux ';
        $sql .= ' WHERE aux.id NOT IN 
                      (SELECT 
                        MAX(a.id) 
                      FROM
                        `type_prestation` tp 
                        INNER JOIN `auxiliaires` a 
                          ON tp.`id` = a.`prestation_id` 
                      WHERE a.`dossier_id` ='.$idDossier.' 
                      GROUP BY tp.`id`)';
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
}
