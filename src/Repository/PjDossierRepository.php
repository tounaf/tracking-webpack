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
}
