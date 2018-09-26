<?php

namespace AppBundle\Service\Google;

use Symfony\Bridge\Twig\TwigEngine;
use Symfony\Component\DependencyInjection\ContainerInterface;

class GoogleAnalyticsService extends GoogleAbstractService
{
    use AbbrToCountryNameTrait;

    public const REPORTS_MAP = [
        'getVisitorsByCountries' => 'Visitors by countries',
        'getBrowserUsage' => 'Browser usage',
        'getTrafficSources' => 'Traffic sources'
    ];

    /**
     * @var \Google_Service_Analytics
     */
    private $analytics;

    /**
     * @var \Symfony\Bridge\Twig\TwigEngine
     */
    private $templating;

    /**
     * GoogleAnalyticsService constructor.
     *
     * @param ContainerInterface $container
     * @param TwigEngine $templating
     *
     * @throws \Google_Exception
     */
    public function __construct(ContainerInterface $container, TwigEngine $templating)
    {
        parent::__construct($container);

        $this->analytics = $this->initAnalytics();
        $this->templating = $templating;
    }

    final public function renderBlockAction(string $methodToCall, array $parameters)
    {

    }

    /**
     * @param string $startDate
     * @param string $endDate
     *
     * @return string | array
     *
     * @throws \Exception
     */
    final public function getVisitorsByCountries(string $startDate = '30daysAgo', string $endDate = 'today')
    {

        $data = [];
        $result = $this->analytics->data_ga->get(
            'ga:' . $this->getProfileIdByName('Чичерина'),
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
     * @param string $startDate
     * @param string $endDate
     *
     * @return array
     *
     * @throws \Exception
     */
    final public function getBrowserUsage(string $startDate = '30daysAgo', string $endDate = 'today')
    {
        $data = [];
        $labels = [];
        $visitors = [];
        $result = $this->analytics->data_ga->get(
            'ga:' . $this->getProfileIdByName('Чичерина'),
            $startDate,
            $endDate,
            'ga:sessions',
            [
                'dimensions' => 'ga:browser',
                'sort'=>'ga:sessions'
            ]
        );

        if (count($result)>0) {
            $rows = $result->getRows();

            foreach ($rows as $row) {

                $labels[] = $row[0];
                $visitors[] = intval($row[1]);
            }

        }

        $data['labels'] = $labels;
        $data['visitors'] = $visitors;

        return $data;

    }

    /**
     * @return \Google_Service_Analytics
     *
     * @throws \Google_Exception
     */
    private function initAnalytics()
    {
        $this->client->setApplicationName($this->container->getParameter('google_analytics_application_name'));
        $this->client->setAuthConfig($this->container->getParameter('google_analytics_credentials'));
        $this->client->setScopes([$this->container->getParameter('google_analytics_scopes')]);

        return new \Google_Service_Analytics($this->client);
    }

    /**
     * @return array
     */
    public function getAccountList()
    {
        $accounts = $this->analytics->management_accounts->listManagementAccounts();
        $accountList = [];
        $i = 0;

        /** @var \Google_Service_Analytics_Account $account */
        foreach ($accounts as $account)
        {
            $accountList[$i]['name'] = $account->getName();
            $accountList[$i]['id'] = $account->getId();
            $i++;
        }

        return $accountList;
    }

    /**
     * @param string $profileName
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function getProfileIdByName(string $profileName)
    {
        $accounts = $this->analytics->management_accounts->listManagementAccounts();

        if (count($accounts->getItems()) > 0) {
            $items = $accounts->getItems();
            /** @var \Google_Service_Analytics_Account $targetAccount */
            $targetAccount = null;

            foreach ($items as $item) {
                if ($item->getName() === $profileName) {
                    $targetAccount = $item;
                }
            }

            if (!$targetAccount instanceof  \Google_Service_Analytics_Account) {
                throw new \Exception(sprintf('No account with the name %s found', $profileName));
            }

            // Get the list of properties for the authorized user.
            $properties = $this->analytics->management_webproperties
                ->listManagementWebproperties($targetAccount->getId());

            if (count($properties->getItems()) > 0) {

                $items = $properties->getItems();
                $firstPropertyId = $items[0]->getId();

                // Get the list of views (profiles) for the authorized user.
                $profiles = $this->analytics->management_profiles
                    ->listManagementProfiles($targetAccount->getId(), $firstPropertyId);

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