<?php declare(strict_types = 1);

namespace Rougin\Slytherin\Debug;

use Rougin\Slytherin\Container\ContainerInterface;
use Rougin\Slytherin\Integration\Configuration;
use Rougin\Slytherin\Integration\IntegrationInterface;
use Rougin\Slytherin\System;

/**
 * Error Handler Integration
 *
 * An integration for defined error handlers to be included in Slytherin.
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class ErrorHandlerIntegration implements IntegrationInterface
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
        /** @var string */
        $environment = $config->get('app.environment', 'development');

        if ($environment !== 'development') return $container;

        $handler = new ErrorHandler($environment);

        if (interface_exists('Whoops\RunInterface'))
        {
            $whoops = new \Whoops\Run;

            $handler = new WhoopsErrorHandler($whoops, $environment);
        }

        return $container->set(System::DEBUGGER, $handler);
    }
}
