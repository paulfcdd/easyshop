<?php

namespace AppBundle\Twig;

use AppBundle\Controller\AppController;
use AppBundle\Entity as Entity;
use AppBundle\Repository\ProductRepository;
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
        ];
    }

    /**
     * @param string $targetFolder
     * @param string $fileName
     * @return mixed|string
     */
    public function getAsset(string $targetFolder, string $fileName)
    {
        $pattern = '/web/template/%theme_name%/%target_folder%/%file_name%';
        $pattern = str_replace(
            ['%theme_name%', '%target_folder%', '%file_name%'],
            [$this->app->getActiveTemplate()->getName(), $targetFolder, $fileName],
            $pattern
        );

        return $pattern;
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
}