<?php

namespace Rougin\Slytherin\Integration;

/**
 * Whoops Integration
 *
 * An integration for Whoops to be included in Slytherin.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class WhoopsIntegration implements IntegrationInterface
{
    /**
     * Defines the specified integration.
     *
     * @param  \Rougin\Slytherin\Container\ContainerInterface $container
     * @return \Rougin\Slytherin\Container\ContainerInterface
     */
    public function define(\Rougin\Slytherin\Container\ContainerInterface $container)
    {
        $container->set('Whoops\Run', new \Whoops\Run);

        return $container;
    }
}
