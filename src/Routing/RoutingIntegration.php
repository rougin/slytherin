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
     * Defines the specified integration.
     *
     * @param  \Rougin\Slytherin\Container\ContainerInterface $container
     * @param  \Rougin\Slytherin\Integration\Configuration    $config
     * @return \Rougin\Slytherin\Container\ContainerInterface
     */
    public function define(ContainerInterface $container, Configuration $config)
    {
        $dispatcher = new Dispatcher;

        $router = $config->get('app.router', new Router);

        if (interface_exists('FastRoute\Dispatcher')) {
            $dispatcher = new FastRouteDispatcher;
        }

        if (class_exists('Phroute\Phroute\Dispatcher')) {
            $resolver = new PhrouteResolver($container);

            $dispatcher = new PhrouteDispatcher(null, $resolver);
        }

        $container->set('Rougin\Slytherin\Routing\DispatcherInterface', $dispatcher);

        $container->set('Rougin\Slytherin\Routing\RouterInterface', $router);

        return $container;
    }
}
