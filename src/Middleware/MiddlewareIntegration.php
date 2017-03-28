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
        $middleware = new \Rougin\Slytherin\Middleware\Middleware;

        if (class_exists('Zend\Stratigility\MiddlewarePipe')) {
            $pipe = new \Zend\Stratigility\MiddlewarePipe;

            $middleware = new \Rougin\Slytherin\Middleware\StratigilityMiddleware($pipe);
        }

        foreach ($config->get('app.middlewares', array()) as $item) {
            $item = is_callable($item) ? $item : new $item;

            $middleware->push($item);
        }

        $container->set('Rougin\Slytherin\Middleware\MiddlewareInterface', $middleware);

        return $container;
    }
}
