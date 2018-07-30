<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class CategoryRepository extends EntityRepository
{
    public const ALIAS = 'cat';

    /**
     * @return mixed
     */
    public function getMenuCategories()
    {
        $query = $this->createQueryBuilder(self::ALIAS)
            ->select()
            ->where(self::ALIAS . '.showInMenu = :showInMenu')
            ->setParameter('showInMenu', true)
            ->getQuery();

        return $query->getResult();
    }
}