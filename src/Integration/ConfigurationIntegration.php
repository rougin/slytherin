<?php

namespace Rougin\Slytherin\Integration;

use Rougin\Slytherin\Integration\Configuration;
use Rougin\Slytherin\Container\ContainerInterface;

/**
 * Configuration Integration
 *
 * Integrates Configuration inside the specified container.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class ConfigurationIntegration implements \Rougin\Slytherin\Integration\IntegrationInterface
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
        $container->set('Rougin\Slytherin\Configuration', $config);
        $container->set('Rougin\Slytherin\Integration\Configuration', $config);

        return $container;
    }
}
