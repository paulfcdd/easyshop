<?php


namespace AppBundle\Controller\Admin\System\Google;


use AppBundle\Controller\Admin\AdminController;

abstract class AbstractGoogleController extends AdminController
{
    abstract protected function getConfig();
    abstract protected function setConfig(array $config);
}