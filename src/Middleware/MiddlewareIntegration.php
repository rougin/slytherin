<?php

namespace Rougin\Slytherin\Middleware;

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
     * @param  array                                          $config
     * @return \Rougin\Slytherin\Container\ContainerInterface
     */
    public function define(\Rougin\Slytherin\Container\ContainerInterface $container, array $config = array())
    {
        $middleware = new \Rougin\Slytherin\Middleware\VanillaMiddleware;

        if (class_exists('Zend\Stratigility\MiddlewarePipe')) {
            $middleware = new \Zend\Stratigility\MiddlewarePipe;
            $middleware = new \Rougin\Slytherin\Middleware\StratigilityMiddleware($middleware);
        }

        foreach ($config['app']['middlewares'] as $item) {
            $item = is_callable($item) ? $item : new $item;

            $middleware->push($item);
        }

        $container->set('Rougin\Slytherin\Middleware\MiddlewareInterface', $middleware);

        return $container;
    }
}
