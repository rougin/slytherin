<?php

namespace Rougin\Slytherin\Container;

/**
 * Delegate Interface
 *
 * An interface for delegate lookup feature in the PSR-11 standard.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
interface DelegateInterface
{
    /**
     * Delegate a container to be checked for services.
     *
     * @param  \Psr\Container\ContainerInterface $container
     * @return self
     */
    public function delegate(\Psr\Container\ContainerInterface $container);
}
