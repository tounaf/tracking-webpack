<?php

namespace App\Repository;

use App\Entity\PjDossier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method PjDossier|null find($id, $lockMode = null, $lockVersion = null)
 * @method PjDossier|null findOneBy(array $criteria, array $orderBy = null)
 * @method PjDossier[]    findAll()
 * @method PjDossier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PjDossierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PjDossier::class);
    }

    public function listPjDossier($extraParams, int $id, $count = false)
    {

        $sql =$count?' SELECT COUNT(pjd.`id`) AS record ' : 'SELECT 
                      pjd.`id`,
                      d.`reference_dossier` as numero,
                      d.`date_litige` as dateAjoutDossier,
                      i.`libelle` AS informationPJ,
                      pjd.filename AS lien
                   ';

        $sql .= "FROM
                      pj_dossier pjd
                  INNER JOIN dossier d 
                    ON d.id = pjd.dossier_id 
                  INNER JOIN information_pj i
                    ON i.`id` = pjd.`information_pj_id` ";
        $sql .= ' WHERE d.id ='.$id;
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
