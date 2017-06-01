<?php

namespace Rougin\Slytherin\Routing;

use Rougin\Slytherin\Integration\Configuration;
use Rougin\Slytherin\Container\ContainerInterface;

/**
 * Routing Integration
 *
 * An integration for Slytherin's Routing packages.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class RoutingIntegration implements \Rougin\Slytherin\Integration\IntegrationInterface
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
        $dispatcher = new \Rougin\Slytherin\Routing\Dispatcher;

        $router = $config->get('app.router', new \Rougin\Slytherin\Routing\Router);

        if (interface_exists('FastRoute\Dispatcher')) {
            $dispatcher = new \Rougin\Slytherin\Routing\FastRouteDispatcher;
        }

        if (class_exists('Phroute\Phroute\Dispatcher')) {
            $resolver = new \Rougin\Slytherin\Routing\PhrouteResolver($container);

            $dispatcher = new \Rougin\Slytherin\Routing\PhrouteDispatcher(null, $resolver);
        }

        $container->set('Rougin\Slytherin\Routing\DispatcherInterface', $dispatcher);
        $container->set('Rougin\Slytherin\Routing\RouterInterface', $router);

        return $container;
    }
}
