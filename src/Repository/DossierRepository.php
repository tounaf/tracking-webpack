<?php

namespace App\Repository;

use App\Entity\Dossier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Dossier|null find($id, $lockMode = null, $lockVersion = null)
 * @method Dossier|null findOneBy(array $criteria, array $orderBy = null)
 * @method Dossier[]    findAll()
 * @method Dossier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DossierRepository extends ServiceEntityRepository
{
    private $column = array(
        'reference',
        'nom',
        'categorie',
        'entite',
        'statut'
    );
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Dossier::class);
    }

    public function ajaxlistDossier($data, $extraParams, $count = false)
    {
        $sql = $count?' SELECT COUNT(d.`id`) AS record ' : 'SELECT 
                    d.`id`,
                    d.`reference_dossier` AS reference,
                    d.`nom_dossier` AS nom ,
                    c.`libelle` AS categorie,
                    r.`libele` AS entite,
                    s.`libele` AS statut
               ';
        $sql .= '  FROM dossier d ';
        $sql .=' INNER JOIN `categorie_litige` AS c ON c.`id` = d.`categorie_id` ';
        $sql .=' INNER JOIN societe r ON r.`id` = d.`raison_social_id` ';
        $sql .=' INNER JOIN statut s ON s.`id` = d.`statut_id` ';
        if ($data) {
            $sqlWhere = [];
            if ($data->getNom() != '') {
                $sqlWhere[] = " d.`nom_dossier` LIKE '%".addslashes($data->getNom())."%'";
            }
            if ($data->getReference() != '') {
                $sqlWhere[] = " d.`reference_dossier` LIKE '%".addslashes($data->getReference())."%'";
            }
            if ($data->getCategorie() != '') {
                $sqlWhere[] = " c.`libelle` LIKE '%".addslashes($data->getCategorie())."%'";
            }
            if ($data->getEntite() != '') {
                $sqlWhere[] = " r.`libele` LIKE '%".addslashes($data->getEntite())."%'";
            }
            if ($data->getStatut() != '') {
                $sqlWhere[] = " s.`libele` LIKE '%".addslashes($data->getStatut())."%'";
            }
            if (count($sqlWhere)> 0) {
                $sql .= ' WHERE '. implode(' AND ', $sqlWhere);
            }
        }
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
