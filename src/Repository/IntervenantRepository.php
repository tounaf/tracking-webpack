<?php

namespace App\Repository;

use App\Entity\Intervenant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Intervenant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Intervenant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Intervenant[]    findAll()
 * @method Intervenant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IntervenantRepository extends ServiceEntityRepository
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
        parent::__construct($registry, Intervenant::class);
    }

    /**
     * @param $dossierId
     * @return mixed[]
     * @throws \Doctrine\DBAL\DBALException
     */
    public function findLatestIntervenant($dossierId)
    {
        $query = "SELECT * FROM intervenant AS i where i.dossier_id = $dossierId ORDER by id desc  LIMIT 1";
        return $this->getEntityManager()->getConnection()->executeQuery($query)->fetchAll();
    }

    /**
     * @param $extraParams
     * @param $idDossier
     * @param bool $count
     * @return mixed[]
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getListIntervenant( $extraParams,$idDossier, $count = false)
    {
        $sql = $count?' SELECT COUNT(d.`id`) AS record ' : 'SELECT 
                    i.`id`,
                    i.`nom_prenom` AS nomPrenom,
                    i.`convenu` AS convenu ,
                    i.`payer` AS payer,
                    i.`reste_payer` AS reste_payer,
                    i.`statut_intervenant` AS statuts,
                    d.`code` AS devise,
                    p.`libelle` AS prestation,
                    pj.`filename` AS filename
               ';
        $sql .= '  FROM intervenant i ';
        $sql .=' INNER JOIN `devise` AS d ON d.`id` = i.`devise_conv_int_id` ';
        $sql .=' INNER JOIN type_prestation p ON p.`id` = i.`prestation_id` ';
        $sql .=' INNER JOIN pj_intervenant pj ON i.`id` = pj.`intervenant_id` ';
        $sql .=' WHERE i.dossier_id = '.$idDossier;
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
