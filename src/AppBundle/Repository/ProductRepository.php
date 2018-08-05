<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ProductRepository extends EntityRepository
{
    public const ALIAS = 'pro';

    public function getAllProducts()
    {
        $query = $this->createQueryBuilder(self::ALIAS)
            ->select()
            ->getQuery();

        return $query->getResult();
    }
}