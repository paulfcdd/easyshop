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

    /**
     * @param int $categoryId
     * @param string $categoryName
     *
     * @return array
     */
    public function getCategoryProducts(int $categoryId, string $categoryName)
    {
        $products = [];
        $criteria = [
            'id' => $categoryId,
            'name' => $categoryName
        ];

        /** @var \AppBundle\Entity\Category $category */
        $category = $this->findOneBy($criteria);

        if (empty($category->getChildren()->toArray())) {
            $products = $category->getProducts()->toArray();
        } else {
            /** @var \AppBundle\Entity\Category $child */
            foreach ($category->getChildren() as $child) {
                $products = array_merge($products,$child->getProducts()->toArray());
            }
        }

        return $products;
    }
}