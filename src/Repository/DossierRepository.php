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

    public function checkConditionProfile($user, $sqlWhere){
        if($user->getProfile()->getCode() == 'ROLE_JURISTE'){
            $sqlWhere[] = " r.id =".$user->getSociete()->getId(). " AND pf.code = 'ROLE_JURISTE'";
        }
        if($user->getProfile()->getCode() == 'ROLE_ADMIN'){
            $sqlWhere[] = " r.id =".$user->getSociete()->getId();
        }
        if($user->getProfile()->getCode() == 'ROLE_SUPERADMIN'){
            return $sqlWhere;
        }
        return $sqlWhere;
    }

    public function  checkDataSearch($data, $sqlWhere){

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
        return $sqlWhere;
    }

    public function ajaxlistDossier($data, $extraParams, $count = false, $user)
    {
        $sql = $count?' SELECT COUNT(d.`id`) AS record ' : 'SELECT 
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
        $sql .=' INNER JOIN `categorie_litige` AS c ON c.`id` = d.`categorie_id` ';
        $sql .=' INNER JOIN societe r ON r.`id` = d.`raison_social_id` ';
        $sql .=' INNER JOIN statut s ON s.`id` = d.`statut_id` ';
        $sql .=' INNER JOIN user us ON d.`user_en_charge_id` = us.`id` ';
        $sql .=' INNER JOIN profil pf ON pf.`id` = us.`profile_id` ';

        $sqlWhere = [];
        if ($data) {
            $sqlWhere = $this->checkDataSearch($data, $sqlWhere);
        }
        $sqlWhere = $this->checkConditionProfile($user, $sqlWhere);
        if (count($sqlWhere)> 0) {
            $sql .= ' WHERE '. implode(' AND ', $sqlWhere);
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
//listing dossier with Pj
    public function getAllDossier(){
        $sql = "SELECT  D.id, D.`date_litige`,infoPj.`libelle`,infoPj.`filename` FROM dossier AS D LEFT JOIN dossier_information_pj AS di ON D.id = di.`dossier_id`
                LEFT JOIN information_pj AS infoPj ON infoPj.`id` = di.`information_pj_id`";
        $qb = $this->getEntityManager()->getConnection()->prepare($sql);
        $qb->execute();
        $result = $qb->fetchAll();
        return $result;
    }

    public function getDossierById($id){
        /*$sql = "SELECT  * FROM dossier AS D LEFT JOIN dossier_information_pj AS di ON D.id = di.`dossier_id`
                LEFT JOIN information_pj AS infoPj ON infoPj.`id` = di.`information_pj_id`
                WHERE D.`id` =".$id;*/
        $sql = "SELECT  D.id, D.`date_litige`,infoPj.`libelle`,infoPj.`filename` FROM dossier AS D LEFT JOIN dossier_information_pj AS di ON D.id = di.`dossier_id`
                LEFT JOIN information_pj AS infoPj ON infoPj.`id` = di.`information_pj_id`
                LEFT JOIN sub_dossier AS sd ON sd.id = D.id
                WHERE D.`id` =".$id;
        $qb = $this->getEntityManager()->getConnection()->prepare($sql);
        $qb->execute();
        $result = $qb->fetchAll();
        return $result;
    }

    public function getDataInfoPjByDossierId($dossierId)
    {
        $sql = "SELECT  D.id id_dossier, infoPj.id id_infoPj, infoPj.`libelle`, infoPj.`filename` 
                FROM dossier AS D LEFT JOIN dossier_information_pj AS di ON D.id = di.`dossier_id`
                LEFT JOIN information_pj AS infoPj ON infoPj.`id` = di.`information_pj_id`
                WHERE D.`id` =".$dossierId;
        $qb = $this->getEntityManager()->getConnection()->prepare($sql);
        $qb->execute();
        $result = $qb->fetchAll();
        return $result;
    }


    public function getInfoPjByDossierId($dossierId){
        $sql = "SELECT * FROM dossier_information_pj where dossier_id =".$dossierId;
        $qb = $this->getEntityManager()->getConnection()->prepare($sql);
        $qb->execute();
        $result = $qb->fetchAll();
        return $result;
    }

    public function getDataDossierInfoPj($dossierId){
        $data = $this->getInfoPjByDossierId($dossierId);
        $dataDossier = array();
        if(!empty($data) && is_array($data)){
            foreach($data as $value){
                $dataDossier = array(
                    'dossier_id' => $value['dossier_id'],
                    'information_pj_id' => $value['information_pj_id']
                );
            }
            return $dataDossier;
        }
    }



}
