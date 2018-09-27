<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Controller\AppController;
use AppBundle\Service\Google\GoogleAnalyticsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Yaml\Yaml;

class AdminController extends AppController
{
    /**
     * @var GoogleAnalyticsService
     */
    private $googleAnalytics;

    /**
     * AdminController constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param GoogleAnalyticsService $googleAnalyticsService
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        GoogleAnalyticsService $googleAnalyticsService
    )
    {
        parent::__construct($entityManager);
        $this->googleAnalytics = $googleAnalyticsService;
    }

    /**
     * @Route("/admin", name="app.admin.index")
     * @
     * @throws \Exception
     */
    public function indexAction()
    {
        $gaConfig = Yaml::parseFile($this->getParameter('google_analytics_config'));

        return $this->render('@App/admin/layout/index.html.twig', [
            'gaConfig' => $gaConfig,
        ]);

    }
}