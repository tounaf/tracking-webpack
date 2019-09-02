<?php
/**
 * Created by PhpStorm.
 * User: nambinina2
 * Date: 21/08/2019
 * Time: 12:03
 */

namespace App\Repository;


use Doctrine\ORM\EntityRepository;

class FosUserRepository extends EntityRepository
{
    /**
     * @param null $user
     * @return mixed
     */
    public function listUserBySociete($user = null, $isAdmin = false)
    {
        $societeId = $user->getSociete()?$user->getSociete()->getId():'';
        $query = $this->createQueryBuilder('u');

        $query
            ->innerJoin('u.societe' ,'s');
        if (!$isAdmin) {

            $query
                ->andWhere('s.id = :id')
                ->setParameter('id', $societeId)
            ;
        }
        $list = $query->getQuery()->getResult();
        return $list;
    }
}