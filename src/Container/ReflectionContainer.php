<?php

namespace Rougin\Slytherin\Container;

// [NOTE] PHP 5.3–5.6 forbids importing a class -----
// with the same short name as one already defined --
// in the current namespace.
use Psr\Container\ContainerInterface as PsrInterface;
// --------------------------------------------------

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
     * @param \Psr\Container\ContainerInterface $container
     */
    public function __construct(PsrInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Resolves the specified parameters from
     * a container.
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
