<?php

namespace Rougin\Slytherin\Routing;

use Rougin\Slytherin\Container\ContainerInterface;
use Rougin\Slytherin\Integration\Configuration;
use Rougin\Slytherin\Integration\IntegrationInterface;

/**
 * Routing Integration
 *
 * An integration for Slytherin's Routing packages.
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
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
     * @param  \Rougin\Slytherin\Container\ContainerInterface $container
     * @param  \Rougin\Slytherin\Integration\Configuration    $config
     * @return \Rougin\Slytherin\Container\ContainerInterface
     */
    public function define(ContainerInterface $container, Configuration $config)
    {
        $hasFastroute = interface_exists('FastRoute\Dispatcher');

        $wantFastroute = $this->preferred === 'fastroute';

        $hasPhroute = class_exists('Phroute\Phroute\Dispatcher');

        $wantPhroute = $this->preferred === 'phroute';

        $dispatcher = new Dispatcher;

        $router = $config->get('app.router', new Router);

        $empty = $this->preferred === null;

        if (($empty || $wantFastroute) && $hasFastroute)
        {
            $dispatcher = new FastRouteDispatcher;
        }

        if (($empty || $wantPhroute) && $hasPhroute)
        {
            $dispatcher = new PhrouteDispatcher;
        }

        $container->set('Rougin\Slytherin\Routing\DispatcherInterface', $dispatcher);

        $container->set('Rougin\Slytherin\Routing\RouterInterface', $router);

        return $container;
    }
}
