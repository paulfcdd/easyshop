<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Product;
use Doctrine\ORM\EntityRepository;

class ProductRepository extends EntityRepository
{
    public const ALIAS = 'pro';

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getBaseQb()
    {
        return $this->createQueryBuilder(self::ALIAS);
    }

    /**
     * @return mixed
     */
    public function getAllProducts()
    {
        $query = $this->getBaseQb()
            ->select()
            ->getQuery();

        return $query->getResult();
    }

    /**
     * @param int $limit
     * @param string $order
     *
     * @return mixed
     */
    public function getFeaturedProducts(int $limit, string $order)
    {
        $query = $this->getBaseQb()
            ->orderBy(self::ALIAS . '.name', $order)
            ->where(self::ALIAS . '.featured = :featured')
            ->setParameter('featured', true)
            ->setMaxResults($limit)
            ->getQuery();

        return $query->getResult();
    }
}