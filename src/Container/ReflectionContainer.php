<?php

namespace Rougin\Slytherin\Container;

Interop::register('ReflectionContainer');

/**
 * @package Slytherin
 *
 * @method mixed   get(string $id)
 * @method boolean has(string $id)
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class ReflectionContainer extends PsrReflectionContainer
{
    /**
     * @param \Psr\Container\ContainerInterface $container
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * Resolves the specified parameters from a container.
     *
     * @param \ReflectionFunctionAbstract $reflector
     * @param array<string, mixed>        $parameters
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
