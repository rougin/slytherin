<?php

namespace Rougin\Slytherin\Middleware;

use Rougin\Slytherin\Integration\Configuration;
use Rougin\Slytherin\Container\ContainerInterface;

/**
 * Middleware Integration
 *
 * An integration for Slytherin's Middleware packages.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class MiddlewareIntegration implements \Rougin\Slytherin\Integration\IntegrationInterface
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
        $dispatcher = new \Rougin\Slytherin\Middleware\Dispatcher;

        if (class_exists('Zend\Stratigility\MiddlewarePipe')) {
            $pipe = new \Zend\Stratigility\MiddlewarePipe;

            $dispatcher = new \Rougin\Slytherin\Middleware\StratigilityDispatcher($pipe);
        }

        foreach ($config->get('app.middlewares', array()) as $item) {
            $middleware = is_callable($item) ? $item : new $item;

            $dispatcher->push($middleware);
        }

        // NOTE: To be removed in v1.0.0. Use Middleware\DispatcherInterface instead.
        $container->set('Rougin\Slytherin\Middleware\MiddlewareInterface', $dispatcher);
        $container->set('Rougin\Slytherin\Middleware\DispatcherInterface', $dispatcher);

        return $container;
    }
}
