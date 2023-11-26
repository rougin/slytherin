<?php

namespace Rougin\Slytherin\Sample\Packages;

use Rougin\Slytherin\Application;
use Rougin\Slytherin\Container\ContainerInterface;
use Rougin\Slytherin\Integration\Configuration;
use Rougin\Slytherin\Integration\IntegrationInterface;
use Rougin\Slytherin\Sample\Retuor;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class SamplePackage implements IntegrationInterface
{
    public function define(ContainerInterface $container, Configuration $config)
    {
        // Sets the default timezone ------------
        $timezone = $config->get('app.timezone');

        date_default_timezone_set($timezone);
        // --------------------------------------

        /** @var \Rougin\Slytherin\Routing\RouterInterface */
        $router = $container->get(Application::ROUTER);

        // Merge new routes to existing ---
        $new = new Retuor;

        $router->merge($new->routes());
        // --------------------------------

        $container->set(Application::ROUTER, $router);

        return $container;
    }
}