<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Controller\AppController;
use AppBundle\Service\Google\GoogleAnalyticsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;

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

        return $this->render('@App/admin/layout/index.html.twig', [
            'visitorsByCountry' => $this->googleAnalytics->getVisitorsByCountries('100daysAgo', 'today'),
            'chartLabels' => json_encode($this->googleAnalytics->getBrowserUsage('100daysAgo')['labels']),
            'chartVisitors' => json_encode($this->googleAnalytics->getBrowserUsage('100daysAgo')['visitors']),

        ]);

    }
}