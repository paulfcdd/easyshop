<?php

namespace AppBundle\Twig;

use AppBundle\Controller\AppController;
use AppBundle\Service\Google\GoogleAnalyticsService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Twig\TwigFunction;

class GoogleAnalyticsExtension extends AppExtension
{
    private $googleAnalyticsService;

    public function __construct(
        \AppBundle\Controller\AppController $app,
        \Symfony\Component\DependencyInjection\ContainerInterface $container,
        GoogleAnalyticsService $googleAnalyticsService
    ) {
        parent::__construct($app, $container);
        $this->googleAnalyticsService = $googleAnalyticsService;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('render_report_block', [$this, 'renderReportBlock']),
        ];
    }

    public function renderReportBlock(array $config)
    {

    }

}