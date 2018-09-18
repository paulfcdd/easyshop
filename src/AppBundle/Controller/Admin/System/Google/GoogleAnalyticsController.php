<?php

namespace AppBundle\Controller\Admin\System\Google;

use AppBundle\Controller\Admin\AdminController;
use AppBundle\Service\Google\AbbrToCountryNameTrait;
use AppBundle\Service\Google\GoogleAnalyticsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;

class GoogleAnalyticsController extends AdminController
{
    use AbbrToCountryNameTrait;

    private $googleAnalyticsService;

    public function __construct(EntityManagerInterface $entityManager, GoogleAnalyticsService $googleAnalyticsService)
    {
        parent::__construct($entityManager, $googleAnalyticsService);

        $this->googleAnalyticsService = $googleAnalyticsService;
    }

    /**
     * @Route("/admin/system/google-analytics", name="app.admin.system.google_analytics")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */
    public function indexAction()
    {

        return $this->render('@App/admin/system/google/analytics.html.twig', [
            'visitorsByCountry' => $this->googleAnalyticsService->getVisitorsByCountries('100daysAgo')
        ]);
    }

    /**
     * @return false|string
     *
     * @throws \Google_Exception
     */
    public function getSessionsByCountriesJson()
    {
        $result = $this->getAnalytics()->data_ga->get(
            'ga:' . $this->getFirstProfileId(),
            '30daysAgo',
            'today',
            'ga:sessions, ga:pageviews',
            [
                'dimensions' => 'ga:country',
                'sort'=>'-ga:sessions'
            ]
        );

        $data = [];

        if (count($result) > 0) {
            $rows = $result->getRows();

            foreach ($rows as $row) {
                $data[$this->getCountryCodeByName($row[0])] = $row[1];
            }

        }

        return json_encode($data);
    }

    /**
     * @return \Google_Service_Analytics
     *
     * @throws \Google_Exception
     */
    private function getAnalytics()
    {
        /** @var \Google_Client $client */
        $client = new \Google_Client();
        $client->setApplicationName($this->getParameter('google_analytics_application_name'));
        $client->setAuthConfig($this->getParameter('google_analytics_auth_config'));
        $client->setScopes([$this->getParameter('google_analytics_scopes')]);
        $analytics = new \Google_Service_Analytics($client);

        return $analytics;
    }

    /**
     * @return mixed
     *
     * @throws \Google_Exception | \Exception
     */
    private function getFirstProfileId()
    {
        $accounts = $this->getAnalytics()->management_accounts->listManagementAccounts();

        if (count($accounts->getItems()) > 0) {
            $items = $accounts->getItems();
            $firstAccountId = $items[0]->getId();

            // Get the list of properties for the authorized user.
            $properties = $this->getAnalytics()->management_webproperties
                ->listManagementWebproperties($firstAccountId);

            if (count($properties->getItems()) > 0) {
                $items = $properties->getItems();
                $firstPropertyId = $items[0]->getId();

                // Get the list of views (profiles) for the authorized user.
                $profiles = $this->getAnalytics()->management_profiles
                    ->listManagementProfiles($firstAccountId, $firstPropertyId);

                if (count($profiles->getItems()) > 0) {
                    $items = $profiles->getItems();

                    // Return the first view (profile) ID.
                    return $items[0]->getId();

                } else {
                    throw new \Exception('No views (profiles) found for this user.');
                }
            } else {
                throw new \Exception('No properties found for this user.');
            }
        } else {
            throw new \Exception('No accounts found for this user.');
        }
    }
}