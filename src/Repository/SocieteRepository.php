<?php

namespace App\Repository;

use App\Entity\FosUser;
use App\Entity\Societe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Societe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Societe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Societe[]    findAll()
 * @method Societe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SocieteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Societe::class);
    }

    /**
     * @param FosUser $user
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getSocieteByRole(FosUser $user) {
        $query = $this->createQueryBuilder('s');
        $query->andWhere('s.enable = true');
        if ($user->hasRole('ROLE_SUPERADMIN')){
            return $query;
        } elseif($user->hasRole('ROLE_ADMIN')) {
            $query
                ->andWhere('s.id = :id');
        }
        elseif ($user->hasRole('ROLE_JURISTE')) {
            $query
                ->andWhere('s.id = :id')
                ;
        }
        $query->setParameter('id',$user->getSociete()->getId());
        return $query;
    }

    /**
     * @param null $userC
     * @return \Doctrine\ORM\QueryBuilder
     * getSociete
     */
    public function getSocietecharge($userC = null){
        $societeId = $userC->getSociete()?$userC->getSociete()->getId():'';
        $userId = $userC->getId();
        $query = $this->createQueryBuilder('u');
        $query->andWhere('u.enable = true')
//            ->andWhere('u.id = :id')
            ->andWhere('u.id = :soc')
//            ->setParameter('uid', $userId)
//            ->setParameter('id', $userId)
            ->setParameter('soc', $societeId);
        return $query;
    }
}
