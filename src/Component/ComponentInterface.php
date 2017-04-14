<?php

namespace Rougin\Slytherin\Component;

/**
 * Component Interface
 *
 * An interface for handling components.
 * NOTE: To be removed in v1.0.0. Use "Integration" instead.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
interface ComponentInterface extends \Rougin\Slytherin\Integration\IntegrationInterface
{
    /**
     * Sets the component. Can also add it to the container.
     *
     * @param  \Interop\Container\ContainerInterface &$container
     * @return void
     */
    public function set(\Interop\Container\ContainerInterface &$container);
}
