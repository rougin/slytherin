<?php

namespace Rougin\Slytherin\Sample\Packages;

use Rougin\Slytherin\Container\ContainerInterface;
use Rougin\Slytherin\Integration\Configuration;
use Rougin\Slytherin\Integration\IntegrationInterface;
use Rougin\Slytherin\Routing\RouterInterface;
use Rougin\Slytherin\Sample\Retuor;
use Rougin\Slytherin\System;
use Rougin\Slytherin\System\Errors\RouterNotFound;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class SamplePackage implements IntegrationInterface
{
    /**
     * @param \Rougin\Slytherin\Container\ContainerInterface $container
     * @param \Rougin\Slytherin\Integration\Configuration    $config
     *
     * @return \Rougin\Slytherin\Container\ContainerInterface
     */
    public function define(ContainerInterface $container, Configuration $config)
    {
        // Sets the default timezone ------------
        /** @var string */
        $timezone = $config->get('app.timezone');

        date_default_timezone_set($timezone);
        // --------------------------------------

        $router = $container->get(System::ROUTER);

        // @codeCoverageIgnoreStart
        if (! $router instanceof RouterInterface)
        {
            throw new RouterNotFound($router);
        }
        // @codeCoverageIgnoreEnd

        // Merge new routes to existing ---
        $new = new Retuor;

        $router->merge($new->routes());
        // --------------------------------

        $container->set(System::ROUTER, $router);

        return $container;
    }
}
