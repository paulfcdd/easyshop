<?php

namespace AppBundle\Controller\Admin\System\Google;

use AppBundle\Service\Google\AbbrToCountryNameTrait;
use AppBundle\Service\Google\GoogleAnalyticsService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Yaml\Yaml;

class GoogleAnalyticsController extends AbstractGoogleController
{
    private const GOOGLE_DAYS_AGO_PREFIX = 'daysAgo';
    private const GOOGLE_TODAY_PREFIX = 'today';

    use AbbrToCountryNameTrait;

    private $googleAnalyticsService;

    public function __construct(EntityManagerInterface $entityManager, GoogleAnalyticsService $googleAnalyticsService)
    {
        parent::__construct($entityManager, $googleAnalyticsService);

        $this->googleAnalyticsService = $googleAnalyticsService;
    }

    /**
     * @Route(
     *     "/admin/system/google-analytics",
     *     name="app.admin.system.google_analytics"
     * )
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */
    public function indexAction()
    {

        return $this->render('@App/admin/system/google/analytics.html.twig', [
            'visitorsByCountry' => $this->googleAnalyticsService->getVisitorsByCountries('100daysAgo'),
            'config' => $this->getConfig(),
            'accountList' => $this->googleAnalyticsService->getAccountList(),
            'reportOptions' => $this->googleAnalyticsService::REPORTS_MAP,
        ]);

    }

    /**
     * @Route(
     *     "/admin/system/google-analytics/save-config",
     *     name="app.admin.system.google_analytics_handle_config"
     * )
     *
     * @Method("POST")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function handleConfigForm(Request $request)
    {
        $config = $this->getConfig();
        $config['active_account_id'] = $request->request->get('account_id');
        $config['date_from'] = $this->transformDateToGoogleAnalyticsFormat($request->request->get('date_from'));
        $config['date_to'] = $this->transformDateToGoogleAnalyticsFormat($request->request->get('date_to'));
        $config['reports_to_show'] = $request->request->get('reports_to_show');

        if ($this->setConfig($config)) {
            $this->flashSuccess('Config saved');
            return $this->redirectToRoute('app.admin.system.google_analytics');
        } else {
            $this->flashError('Error');
            return $this->redirectToRoute('app.admin.system.google_analytics');
        }
    }

    /**
     * @return mixed
     */
    protected function getConfig()
    {
        $config = Yaml::parseFile($this->getParameter('google_analytics_config'));

        return $config;
    }

    /**
     * @param array $config
     *
     * @return bool
     */
    protected function setConfig(array $config)
    {
        $yamlConfig = Yaml::dump($config);

        try {
            file_put_contents($this->getParameter('google_analytics_config'), $yamlConfig);
            return true;
        } catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * @param string $date
     *
     * @return string
     */
    protected function transformDateToGoogleAnalyticsFormat(string $date)
    {
        $today = new \DateTime();
        $userDate = \DateTime::createFromFormat('d.m.Y', $date);
        $diff = $userDate->diff($today)->days;
        $preparedValue = $diff > 0 ? $diff . self::GOOGLE_DAYS_AGO_PREFIX : self::GOOGLE_TODAY_PREFIX;

        return $preparedValue;
    }
}