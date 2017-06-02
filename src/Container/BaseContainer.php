<?php

namespace Rougin\Slytherin\Container;

/**
 * Base Container
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class BaseContainer
{
    /**
     * Resolves the specified parameters from a container.
     *
     * @param  \ReflectionFunction|\ReflectionMethod $reflector
     * @param  array                                 $parameters
     * @return array
     */
    public function arguments($reflector, $parameters = array())
    {
        $arguments = array();

        foreach ($reflector->getParameters() as $key => $parameter) {
            $argument = $this->argument($parameter);

            $name = $parameter->getName();

            $arguments[$key] = $argument ?: $parameters[$name];
        }

        return $arguments;
    }
}
