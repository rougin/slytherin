<?php

namespace Rougin\Slytherin\Container;

/**
 * [NOTE] The reason that this was renamed below
 * as "PsrInterface" because PHP 5.3–5.6 forbids
 * importing a class with the same short name as
 * one already defined in the current namespace.
 */

use Psr\Container\ContainerInterface as PsrInterface;

Interop::register('ReflectionContainer');

/**
 * @package Slytherin
 *
 * @property \Psr\Container\ContainerInterface $container
 *
 * @method mixed   get(string $id)
 * @method boolean has(string $id)
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class ReflectionContainer extends PsrReflectionContainer implements PsrInterface
{
    /**
     * @param \Psr\Container\ContainerInterface|null $container
     */
    public function __construct($container = null)
    {
        $this->container = $container ? $container : new Container;
    }

    /**
     * Resolves the specified parameters from
     * a container.
     *
     * @param \ReflectionFunctionAbstract $reflector
     * @param mixed[]                     $parameters
     *
     * @return array<integer, mixed>
     */
    public function getArguments(\ReflectionFunctionAbstract $reflector, $parameters = array())
    {
        $items = $reflector->getParameters();

        $result = array();

        foreach ($items as $key => $item)
        {
            $argument = $this->getArgument($item);

            $name = $item->getName();

            if (array_key_exists($name, $parameters))
            {
                $result[$key] = $parameters[$name];
            }

            if ($argument)
            {
                $result[$key] = $argument;
            }
        }

        return $result;
    }
}
