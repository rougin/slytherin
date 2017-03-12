<?php

namespace Rougin\Slytherin\Integration;

/**
 * Routing Integration
 *
 * An integration for Slytherin's simple Routing package.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class RoutingIntegration implements IntegrationInterface
{
    /**
     * Defines the specified integration.
     *
     * @param  \Rougin\Slytherin\Container\ContainerInterface $container
     * @param  array                                          $configurations
     * @return \Rougin\Slytherin\Container\ContainerInterface
     */
    public function define(\Rougin\Slytherin\Container\ContainerInterface $container, array $configurations = array())
    {
        $dispatcher = new \Rougin\Slytherin\Routing\Vanilla\Dispatcher($configurations['app']['router']);

        $container->set('Rougin\Slytherin\Routing\DispatcherInterface', $dispatcher);

        return $container;
    }
}
