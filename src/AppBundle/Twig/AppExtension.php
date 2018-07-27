<?php

namespace AppBundle\Twig;

use AppBundle\Controller\AppController;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    /**
     * @var \AppBundle\Controller\AppController
     */
    public $app;

    /**
     * AppExtension constructor.
     * @param \AppBundle\Controller\AppController $app
     */
    public function __construct(AppController $app)
    {
        $this->app = $app;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('get_asset', [$this, 'getAsset'])
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
}