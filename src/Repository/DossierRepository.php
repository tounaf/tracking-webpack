<?php

namespace App\Repository;

use App\Entity\Dossier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Entity\Repository;

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

    public function checkConditionProfile($user, $sqlWhere)
    {
        if ($user->getProfile()->getCode() == 'ROLE_JURISTE') {
            $sqlWhere[] = " r.id =" . $user->getSociete()->getId() . " AND pf.code = 'ROLE_JURISTE'";
        }
        if ($user->getProfile()->getCode() == 'ROLE_ADMIN') {
            $sqlWhere[] = " r.id =" . $user->getSociete()->getId();
        }
        if ($user->getProfile()->getCode() == 'ROLE_SUPERADMIN') {
            return $sqlWhere;
        }
        return $sqlWhere;
    }

    public function checkDataSearch($data, $sqlWhere)
    {

        if ($data->getNom() != '') {
            $sqlWhere[] = " d.`nom_dossier` LIKE '%" . addslashes($data->getNom()) . "%'";
        }
        if ($data->getReference() != '') {
            $sqlWhere[] = " d.`reference_dossier` LIKE '%" . addslashes($data->getReference()) . "%'";
        }
        if ($data->getCategorie() != '') {
            $sqlWhere[] = " c.`libelle` LIKE '%" . addslashes($data->getCategorie()) . "%'";
        }
        if ($data->getEntite() != '') {
            $sqlWhere[] = " r.`libele` LIKE '%" . addslashes($data->getEntite()) . "%'";
        }
        if ($data->getStatut() != '') {
            $sqlWhere[] = " s.`libele` LIKE '%" . addslashes($data->getStatut()) . "%'";
        }
        return $sqlWhere;
    }

    public function ajaxlistDossier($data, $extraParams, $count = false, $user)
    {
        $sql = $count ? ' SELECT COUNT(d.`id`) AS record ' : 'SELECT 
                    d.`id`,
                    us.`id` AS user_id,
                    pf.`id` AS profil_id,
                    pf.`code` AS profil_code,
                    d.`reference_dossier` AS reference,
                    d.`nom_dossier` AS nom ,
                    c.`libelle` AS categorie,
                    r.`libele` AS entite,
                    s.`libele` AS statut
               ';
        $sql .= '  FROM dossier d ';
        $sql .= ' INNER JOIN `categorie_litige` AS c ON c.`id` = d.`categorie_id` ';
        $sql .= ' INNER JOIN societe r ON r.`id` = d.`raison_social_id` ';
        $sql .= ' INNER JOIN statut s ON s.`id` = d.`statut_id` ';
        $sql .= ' INNER JOIN user us ON d.`user_en_charge_id` = us.`id` ';
        $sql .= ' INNER JOIN profil pf ON pf.`id` = us.`profile_id` ';

        $sqlWhere = [];
        if ($data) {
            $sqlWhere = $this->checkDataSearch($data, $sqlWhere);
        }
        $sqlWhere = $this->checkConditionProfile($user, $sqlWhere);
        if (count($sqlWhere) > 0) {
            $sql .= ' WHERE ' . implode(' AND ', $sqlWhere);
        }
        if (!$count) {
            if (isset($this->column[$extraParams['orderBy']]) && isset($extraParams['order'])) {
                $sql .= ' ORDER BY ' . $this->column[$extraParams['orderBy']] . ' ' . $extraParams['order'];
            }
            if (isset($extraParams['start']) && isset($extraParams['length'])) {

                $sql .= ' LIMIT ' . $extraParams['start'] . ',' . $extraParams['length'];
            }
        }
        $qb = $this->getEntityManager()->getConnection()->prepare($sql);
        $qb->execute();
        $result = $qb->fetchAll();
        return $result;

    }

    public function getCountRefDossier($soc)
    {
        return $this->createQueryBuilder('d')
            ->select('COUNT(d.id)')
            ->andWhere('d.raisonSocial= :soc')
            ->setParameter('soc', $soc)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @return mixed
     * date d'alerte du dossier d personne en charge
     */
    public function getEmailUserEnCharge()
    {#alerte date
        $query = " SELECT 
                      d.`user_en_charge_id`, d.nom_dossier AS nomDossier,d.reference_dossier AS referenceDossier,s.libele AS libele,d.nom_partie_adverse AS nomPartieAdverse,
                      d.echeance AS echeance,etp.libelle AS libelle,
                      d.id,
                      IF (CURRENT_DATE() BETWEEN t.`datedebut` AND t.datefin, t.`usertransfer_id`,d.`user_en_charge_id`) test,
                      (SELECT email FROM `user` WHERE id=test) AS email
                    FROM
                      dossier d 
                      LEFT JOIN transfertnotification t 
                        ON d.user_en_charge_id = t.usernotif_id #And CURRENT_DATE() BETWEEN t.`datedebut` and t.datefin
                          INNER JOIN  `user` us ON d.user_en_charge_id = us.id
                          INNER JOIN societe s ON d.raison_social_id=s.id
                          INNER JOIN etape_suivante etp ON d.etape_suivante_id=etp.id
                    WHERE DATE(d.alerte_date) = CURRENT_DATE() 
                    GROUP BY d.`id`";
        return $this->getEntityManager()->getConnection()->executeQuery($query)->fetchAll();
        /*  $currentDate = new \DateTime();
          $query = $this->createQueryBuilder('d');
          $query

             // ->select('u.email','u.lastname', 'd.id','d.referenceDossier','d.raisonSocial','d.nomDossier','d.nomPartieAdverse','d.echeance','d.etapeSuivante')
              ->select('u.email','d.referenceDossier','s.libele','d.nomDossier','d.nomPartieAdverse','d.echeance','etp.libelle')
              ->innerJoin('d.userEnCharge','u')
              ->innerJoin('d.raisonSocial','s')
              ->innerJoin('d.etapeSuivante','etp')
              ->andWhere('d.alerteDate = :now')
              ->setParameter('now', $currentDate->format('Y-m-d'));
          return $query->getQuery()->getResult();*/
    }


    /**
     * send notification mailing when dossier no updated in create
     * @return mixed
     */
    public function getUserEnChargDssrNoUpdt()
    {
        $query = "SELECT d.`user_en_charge_id`,d.`created_at`, d.nom_dossier AS nomDossier,d.reference_dossier AS referenceDossier,s.libele AS libele,
                          d.nom_partie_adverse AS nomPartieAdverse, d.echeance AS echeance,etp.libelle AS libelle, IF (CURRENT_DATE() BETWEEN t.`datedebut` AND t.datefin, t.`usertransfer_id`,d.`user_en_charge_id`) test,
                          (SELECT email FROM `user` WHERE id=test) AS email 
                          FROM dossier d 
                          LEFT JOIN transfertnotification t 
                            ON d.user_en_charge_id = t.usernotif_id 
                           LEFT JOIN history h
                            ON d.id = h.`dossier_id`
                           INNER JOIN `user` us 
                            ON d.user_en_charge_id = us.id 
                        INNER JOIN societe s 
                          ON d.raison_social_id = s.id 
                         INNER JOIN etape_suivante etp 
                            ON d.etape_suivante_id = etp.id 
                        WHERE d.created_at < DATE_SUB(CURRENT_DATE(), INTERVAL 10 DAY) OR h.`created_at` < DATE_SUB(CURRENT_DATE(), INTERVAL 10 DAY)
                        GROUP BY d.`id`";
        return $this->getEntityManager()->getConnection()->executeQuery($query)->fetchAll();
    }

}
