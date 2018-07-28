<?php

namespace AppBundle\Twig;

use AppBundle\Controller\AppController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Asset\Context\ContextInterface;
use Symfony\Component\DependencyInjection\Container;
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
    )
    {
        $this->app = $app;
        $this->container = $container;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('get_asset', [$this, 'getAsset']),
            new TwigFunction('render_menu', [$this, 'renderMenu']),
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
}