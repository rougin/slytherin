<?php

namespace Rougin\Slytherin\Routing;

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
     * @param  array                                          $config
     * @return \Rougin\Slytherin\Container\ContainerInterface
     */
    public function define(\Rougin\Slytherin\Container\ContainerInterface $container, array $config = array())
    {
        $router = $config['app']['router'];

        $dispatcher = new \Rougin\Slytherin\Routing\Vanilla\Dispatcher($router);

        if (interface_exists('FastRoute\Dispatcher')) {
            $dispatcher = new \Rougin\Slytherin\Routing\FastRoute\Dispatcher($router);
        }

        if (class_exists('Phroute\Phroute\Dispatcher')) {
            $dispatcher = new \Rougin\Slytherin\Routing\Phroute\Dispatcher($router);
        }

        $container->set('Rougin\Slytherin\Routing\DispatcherInterface', $dispatcher);

        return $container;
    }
}
