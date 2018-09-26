<?php

namespace AppBundle\Twig;

use AppBundle\Controller\AppController;
use AppBundle\Service\Google\GoogleAnalyticsService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Twig\TwigFilter;
use Twig\TwigFunction;

class GoogleAnalyticsExtension extends AppExtension
{
    private $googleAnalyticsService;
    private $environment;

    public function __construct(
        AppController $app,
        ContainerInterface $container, GoogleAnalyticsService $googleAnalyticsService,\Twig_Environment $environment
    ) {
        parent::__construct($app, $container);

        $this->googleAnalyticsService = $googleAnalyticsService;
        $this->environment = $environment;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('to_json', [$this, 'toJson']),
        ];
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('render_google_analytics_widgets', [$this, 'renderGoogleAnalyticsWidgets']),
        ];
    }

    public function renderGoogleAnalyticsWidgets( array $config)
    {
        $reportsToShow = $config['reports_to_show'];
        $classFQN = GoogleAnalyticsService::class;
        $parameters[0] = $config['date_from'];
        $parameters[1] = $config['date_to'];
        /** @var GoogleAnalyticsService $class */
        $class = new $classFQN($this->container);
        $widgetsToRender = [];
        $i = 0;

        foreach ($reportsToShow as $action) {
            $data = call_user_func_array([$class, $action], $parameters);
            $templateName = $this->prepareTemplateNameFromFunctionName($action);
            $widget = $this->environment->render(('@App/admin/partials/'.$templateName.'.html.twig'), [
                'data' => $data
            ]);
            $widgetsToRender[$i] = $widget;
            $i++;
        }

        return $widgetsToRender;
    }

    /**
     * @param array $data
     * @return string
     */
    public function toJson(array $data)
    {
        return json_encode($data);
    }

    private function prepareTemplateNameFromFunctionName(string $functionName)
    {
        $functionName = str_replace('get', '', $functionName);
        $functionNameArray = array_map('strtolower', preg_split('/(?=[A-Z])/', $functionName));
        $templateName = implode('_', $functionNameArray);

        return $templateName;
    }

}