<?php

namespace Rougin\Slytherin\Middleware;

use Rougin\Slytherin\Container\ContainerInterface;
use Rougin\Slytherin\Integration\Configuration;
use Rougin\Slytherin\Integration\IntegrationInterface;
use Rougin\Slytherin\Server\Dispatch;
use Rougin\Slytherin\System;

/**
 * Middleware Integration
 *
 * An integration for Slytherin's Middleware packages.
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class MiddlewareIntegration implements IntegrationInterface
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
        $middleware = System::MIDDLEWARE;

        if (! interface_exists($middleware)) return $container;

        /** @var array<int, mixed> */
        $stack = $config->get('app.middlewares', array());

        $dispatch = new Dispatch($stack);

        return $container->set($middleware, $dispatch);
    }
}
