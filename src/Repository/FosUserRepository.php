<?php
/**
 * Created by PhpStorm.
 * User: nambinina2
 * Date: 21/08/2019
 * Time: 12:03
 */

namespace App\Repository;


use App\Entity\FosUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class FosUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FosUser::class);
    }
    /**
     * @param null $user
     * @return mixed
     */
    public function listUserBySociete($user = null, $isAdmin = false)
    {
        $societeId = $user->getSociete()?$user->getSociete()->getId():'';
        $query = $this->createQueryBuilder('u');
        $query
            ->andWhere('u.id <> :currentUserId')
            ->setParameter('currentUserId', $user->getId());
        if (!$isAdmin) {

            $query
                ->innerJoin('u.societe' ,'s')
                ->andWhere('s.id = :id')
                ->setParameter('id', $societeId)
            ;
        }
        $list = $query->getQuery()->getResult();
        return $list;

    }

    public function getUserCharge($userC = null){
        $societeId = $userC->getSociete()?$userC->getSociete()->getId():'';
        $userId = $userC->getId();
     $query = $this->createQueryBuilder('u');
        $query->andWhere('u.actif = true');
//            ->andWhere('u.id = :id')
        if ($userC->hasRole('ROLE_SUPERADMIN')){
                    return $query;
                }
                else{
            $query
            ->innerJoin('u.societe','s')
            ->andWhere('s.id = :soc')
            ->orderBy('u.id','DESC')
            ->setParameter('soc', $societeId);
    return $query;  }
    }

    /**
     * @param null $userC
     * @return \Doctrine\ORM\QueryBuilder
     * getSociete
     */
    public function getSocieteUser($userC = null){
        $societeId = $userC->getSociete()?$userC->getSociete()->getId():'';
        $userId = $userC->getId();
     $query = $this->createQueryBuilder('u');
        $query->andWhere('u.actif = true')
//            ->andWhere('u.id = :id')

            ->innerJoin('u.societe','s')
            ->andWhere('s.id = :soc')
            ->orderBy('u.id','DESC')
//            ->setParameter('uid', $userId)
//            ->setParameter('id', $userId)
            ->setParameter('soc', $societeId);
    return $query;
    }
}