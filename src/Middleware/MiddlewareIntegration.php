<?php

namespace Rougin\Slytherin\Middleware;

use Rougin\Slytherin\Container\ContainerInterface;
use Rougin\Slytherin\Integration\Configuration;
use Rougin\Slytherin\Integration\IntegrationInterface;
use Rougin\Slytherin\System;
use Zend\Stratigility\MiddlewarePipe;

/**
 * Middleware Integration
 *
 * An integration for Slytherin's Middleware packages.
 *
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
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
     * @param \Rougin\Slytherin\Container\ContainerInterface $container
     * @param \Rougin\Slytherin\Integration\Configuration    $config
     *
     * @return \Rougin\Slytherin\Container\ContainerInterface
     */
    public function define(ContainerInterface $container, Configuration $config)
    {
        /** @var array<integer, mixed> */
        $stack = $config->get('app.middlewares', array());

        $dispatch = new Dispatcher($stack);

        $empty = $this->preferred === null;

        // Use "zend-stratigility" if installed and preferred ------
        $hasZend = class_exists('Zend\Stratigility\MiddlewarePipe');

        $wantZend = $this->preferred === 'stratigility';

        if (($empty || $wantZend) && $hasZend)
        {
            $pipe = new MiddlewarePipe;

            $dispatch = new StratigilityDispatcher($pipe);
        }
        // ---------------------------------------------------------

        return $container->set(System::MIDDLEWARE, $dispatch);
    }
}
