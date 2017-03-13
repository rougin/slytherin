<?php

namespace Rougin\Slytherin\Debug;

/**
 * Error Handler Integration
 *
 * An integration for defined error handlers to be included in Slytherin.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class ErrorHandlerIntegration implements \Rougin\Slytherin\Integration\IntegrationInterface
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
        $handler = new VanillaErrorHandler;

        if (class_exists('Whoops\Run')) {
            $handler = new WhoopsErrorHandler(new \Whoops\Run);
        }

        $handler->setEnvironment($config['app']['environment']);

        $handler->display();

        return $container;
    }
}
