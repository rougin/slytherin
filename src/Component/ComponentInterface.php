<?php

namespace Rougin\Slytherin\Component;

use Psr\Container\ContainerInterface;
use Rougin\Slytherin\Integration\IntegrationInterface;

/**
 * Component Interface
 *
 * An interface for handling components.
 * NOTE: To be removed in v1.0.0. Use "Integration" instead.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
interface ComponentInterface extends IntegrationInterface
{
    /**
     * Sets the component. Can also add it to the container.
     *
     * @param  \Psr\Container\ContainerInterface &$container
     * @return void
     */
    public function set(ContainerInterface &$container);
}
