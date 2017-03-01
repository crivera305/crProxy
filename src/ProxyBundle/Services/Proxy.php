<?php

namespace ProxyBundle\Services;

use Symfony\Component\DependencyInjection\Container;

class ProxyService
{
    /**
     * @var Container
     */
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function makeRequest($endpoint, $options)
    {
        $this->container = $container;
    }

}