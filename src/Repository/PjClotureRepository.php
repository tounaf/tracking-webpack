<?php

namespace App\Repository;

use App\Entity\PjCloture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method PjCloture|null find($id, $lockMode = null, $lockVersion = null)
 * @method PjCloture|null findOneBy(array $criteria, array $orderBy = null)
 * @method PjCloture[]    findAll()
 * @method PjCloture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PjClotureRepository extends ServiceEntityRepository
{

    /**
     * @var array
     */
    private $column = array(
        'id',
        'numero',
        'dateAjoutDossier',
        'informationPJ',
        'lien'
    );

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PjCloture::class);
    }

    public function listPjCloture($extraParams, int $id, $count = false)
    {

        $sql =$count?' SELECT COUNT(pc.`id`) AS record ' : 'SELECT 
                      pc.`id`,
                      d.`reference_dossier` as numero,
                      d.`date_litige` as dateAjoutDossier,
                      i.`libelle` AS informationPJ,
                      pc.name AS lien
                   ';

        $sql .= "FROM
                      pj_cloture pc 
                  INNER JOIN cloture c 
                    ON c.id = pc.cloture_id 
                  INNER JOIN dossier d 
                    ON d.`id` = c.`dossier_id`
                  INNER JOIN information_pj i
                    ON i.`id` = pc.`info_pj_id` ";

        $sql .= ' WHERE c.id ='.$id;

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
