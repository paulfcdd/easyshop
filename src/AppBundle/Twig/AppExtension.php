<?php

namespace AppBundle\Twig;

use AppBundle\Controller\AppController;
use AppBundle\Entity as Entity;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Yaml\Yaml;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    /**
     * @var \AppBundle\Controller\AppController
     */
    private $app;

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $container;

    /**
     * AppExtension constructor.
     *
     * @param \AppBundle\Controller\AppController $app
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function __construct(
        AppController $app,
        ContainerInterface $container
    ) {
        $this->app = $app;
        $this->container = $container;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('get_asset', [$this, 'getAsset']),
            new TwigFunction('render_menu', [$this, 'renderMenu']),
            new TwigFunction('count_category_products', [$this, 'countCategoryProducts']),
            new TwigFunction('get_featured_products', [$this, 'getFeaturedProducts']),
            new TwigFunction('get_breadcrumbs', [$this, 'getBreadcrumbs']),
            new TwigFunction('sort_specifications_by_group', [$this, 'sortSpecificationsByGroup']),
            new TwigFunction('get_product_slug', [$this, 'getProductSlug'])
        ];
    }

    /**
     * @param string $menuName
     *
     * @return array
     */
    public function renderMenu(string $menuName)
    {
        $file = sprintf('%s.yml', $menuName);
        $menu = $this->container->get('kernel')->getRootDir() . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'menu' . DIRECTORY_SEPARATOR . $file;
        $data = Yaml::parseFile($menu);

        return $data;
    }

    /**
     * @param int $categoryId
     * @param string $categoryName
     *
     * @return int
     */
    public function countCategoryProducts(int $categoryId, string $categoryName)
    {
        $categoryProducts = $this->app->entityManager
            ->getRepository(Entity\Category::class)
            ->getCategoryProducts($categoryId, $categoryName);

        $countedProducts = count($categoryProducts);

        return $countedProducts;
    }

    /**
     * @param int $limit
     * @param string $order
     *
     * @return mixed
     */
    public function getFeaturedProducts(int $limit, string $order)
    {
        return $this->app->entityManager
            ->getRepository(Entity\Product::class)
            ->getFeaturedProducts($limit, $order);
    }

    /**
     * @param \AppBundle\Entity\Category $category
     * @param \AppBundle\Entity\Product|null $product
     *
     * @return array
     */
    public function getBreadcrumbs(Entity\Category $category, Entity\Product $product = null)
    {
        return $this->app->renderBreadcrumbsChain($category, $product);
    }

    /**
     * @param \Doctrine\ORM\PersistentCollection $specifications
     *
     * @return array
     */
    public function sortSpecificationsByGroup(PersistentCollection $specifications)
    {
        $sortedSpecifications = [];

        /** @var \AppBundle\Entity\ProductSpecification $specification */
        foreach ($specifications as $specification)
        {
            /** @var \AppBundle\Entity\SpecificationGroup $specificationGroup */
            $specificationGroup = $specification->getSpecification()->getSpecificationGroup();
            /** @var int $specificationGroupId */
            $specificationGroupId = $specificationGroup->getId();

            if (!array_key_exists($specificationGroupId, $sortedSpecifications)) {
                $sortedSpecifications[$specificationGroupId]['name'] = $specificationGroup->getName();
                $sortedSpecifications[$specificationGroupId]['specifications'] = [];
            }

            $sortedSpecifications[$specificationGroupId]['specifications'][] =  $specification;
        }

        return $sortedSpecifications;
    }

    /**
     * @param \AppBundle\Entity\Product $product
     * @return mixed|null|string
     */
    public function getProductSlug(Entity\Product $product)
    {
        if ($product->getSlug()) {
            return $product->getSlug();
        } else {
            return $product->getId();
        }
    }
}