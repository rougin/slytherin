<?php

namespace Rougin\Slytherin\Debug;

use Rougin\Slytherin\Application;
use Rougin\Slytherin\Container\ContainerInterface;
use Rougin\Slytherin\Integration\Configuration;
use Rougin\Slytherin\Integration\IntegrationInterface;

/**
 * Error Handler Integration
 *
 * An integration for defined error handlers to be included in Slytherin.
 * NOTE: To be removed in v1.0.0. Move to "Integration" directory instead.
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

        $handler = new ErrorHandler($environment);

        if (interface_exists('Whoops\RunInterface'))
        {
            $whoops = new \Whoops\Run;

            $handler = new WhoopsErrorHandler($whoops, $environment);
        }

        if ($environment === 'development')
        {
            ini_set('display_errors', '1');

            error_reporting(E_ALL);

            // NOTE: To be removed in v1.0.0. Use $handler->display() instead. ---
            $container->set(Application::ERROR_HANDLER, $handler);
            // -------------------------------------------------------------------
        }

        return $container;
    }
}