<?php

namespace AppBundle\Service\Google;

use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class GoogleAbstractService
{
    /**
     * @var \Google_Client
     */
    public $client;

    /**
     * @var ContainerInterface
     */
    public $container;

    public function __construct(ContainerInterface $container)
    {
        $this->client = new \Google_Client();
        $this->container = $container;
    }
}