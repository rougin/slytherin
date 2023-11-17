<?php

namespace Rougin\Slytherin\Routing;

use Psr\Container\ContainerInterface;

/**
 * Phroute Resolver
 *
 * A handler resolver that uses PSR-11 containers to resolve dependencies.
 *
 * https://github.com/mrjgreen/phroute
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class PhrouteResolver implements \Phroute\Phroute\HandlerResolverInterface
{
    /**
     * @var \Psr\Container\ContainerInterface
     */
    protected $container;

    /**
     * Initializes the resolver instance.
     *
     * @param \Psr\Container\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Create an instance of the given handler.
     *
     * @param  array<int, mixed> $handler
     * @return array<int, mixed>
     */
    public function resolve($handler)
    {
        if (is_array($handler) && is_string($handler[0]))
        {
            $handler[0] = $this->container->get($handler[0]);
        }

        return $handler;
    }
}