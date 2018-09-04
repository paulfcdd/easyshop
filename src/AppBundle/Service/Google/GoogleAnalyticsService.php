<?php

namespace AppBundle\Service\Google;

use AppBundle\Controller\Admin\System\Google\CountriesNameMappingTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

class GoogleAnalyticsService extends GoogleAbstractService
{
    use CountriesNameMappingTrait;

    /**
     * @var \Google_Service_Analytics
     */
    private $analytics;

    /**
     * GoogleAnalyticsService constructor.
     *
     * @param ContainerInterface $container
     *
     * @throws \Google_Exception
     */
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);

        $this->analytics = $this->initAnalytics();
    }

    /**
     * @param string $startDate
     * @param string $endDate
     *
     * @return string
     *
     * @throws \Exception
     */
    public function getVisitorsByCountries(string $startDate = '30daysAgo', string $endDate = 'today')
    {
        $data = [];
        $result = $this->analytics->data_ga->get(
            'ga:' . $this->getFirstProfileId(),
            $startDate,
            $endDate,
            'ga:sessions, ga:pageviews',
            [
                'dimensions' => 'ga:country',
                'sort'=>'-ga:sessions'
            ]
        );

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
    private function initAnalytics()
    {
        $this->client->setApplicationName($this->container->getParameter('google_analytics_application_name'));
        $this->client->setAuthConfig($this->container->getParameter('google_analytics_auth_config'));
        $this->client->setScopes([$this->container->getParameter('google_analytics_scopes')]);

        return new \Google_Service_Analytics($this->client);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    private function getFirstProfileId()
    {
        $accounts = $this->analytics->management_accounts->listManagementAccounts();

        if (count($accounts->getItems()) > 0) {
            $items = $accounts->getItems();
            $firstAccountId = $items[0]->getId();

            // Get the list of properties for the authorized user.
            $properties = $this->analytics->management_webproperties
                ->listManagementWebproperties($firstAccountId);

            if (count($properties->getItems()) > 0) {
                $items = $properties->getItems();
                $firstPropertyId = $items[0]->getId();

                // Get the list of views (profiles) for the authorized user.
                $profiles = $this->analytics->management_profiles
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