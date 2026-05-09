<?php

namespace Rougin\Slytherin\Routing;

use Rougin\Slytherin\Container\ContainerInterface;
use Rougin\Slytherin\Integration\Configuration;
use Rougin\Slytherin\Integration\IntegrationInterface;
use Rougin\Slytherin\System;

/**
 * Routing Integration
 *
 * An integration for Slytherin's Routing packages.
 *
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class RoutingIntegration implements IntegrationInterface
{
    /**
     * @var string|null
     */
    protected $preferred = null;

    /**
     * Defines the specified integration.
     *
     * @param \Rougin\Slytherin\Container\ContainerInterface $container
     * @param \Rougin\Slytherin\Integration\Configuration    $config
     *
     * @return \Rougin\Slytherin\Container\ContainerInterface
     */
    public function define(ContainerInterface $container, Configuration $config)
    {
        $dispatcher = new Dispatcher;

        $router = $config->get('app.router', new Router);

        if ($this->wants('fastroute'))
        {
            $dispatcher = new FastRouteDispatcher;
        }

        if ($this->wants('phroute'))
        {
            $dispatcher = new PhrouteDispatcher;
        }

        $container->set(System::DISPATCHER, $dispatcher);

        return $container->set(System::ROUTER, $router);
    }

    /**
     * Checks the preferred package to be used.
     *
     * @param string $type
     *
     * @return boolean
     */
    protected function wants($type)
    {
        $empty = $this->preferred === null;

        $package = '';

        if ($type === 'fastroute')
        {
            $package = 'FastRoute\RouteCollector';
        }

        if ($type === 'phroute')
        {
            $package = 'Phroute\Phroute\Dispatcher';
        }

        $preferred = $this->preferred === $type;

        $exists = class_exists($package);

        return ($empty || $preferred) && $exists;
    }
}
