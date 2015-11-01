<?php

namespace Rougin\Slytherin\IoC;

use Auryn\Injector;
use Rougin\Slytherin\IoC\DependencyInjectorInterface;

/**
 * Auryn
 *
 * A simple implementation of a dependency injector that is built on top of
 * Daniel Lowrey' Auryn Inversion Of Control (IoC) Dependency Injector.
 *
 * https://github.com/rdlowrey/auryn
 * 
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Auryn implements DependencyInjectorInterface
{
    protected $injector;

    /**
     * @param Injector $injector
     */
    public function __construct(Injector $injector)
    {
        $this->injector = $injector;
    }

    /**
     * Defines an alias for all occurrences of a given typehint.
     *
     * Use this method to specify implementation classes for interface and
     * abstract class typehints.
     *
     * @param  string $original The typehint to replace
     * @param  string $alias The implementation name
     * @return self
     */
    public function alias($original, $alias)
    {
        return $this->injector->alias($original, $alias);
    }

    /**
     * Defines instantiation directives for the specified class.
     *
     * @param  string $name The class whose constructor arguments to define
     * @param  array  $args An array mapping parameter names to values
     * @return self
     */
    public function define($name, array $args)
    {
        return $this->injector->define($name, $args);
    }

    /**
     * Delegates the creation of $name instances to the specified callable.
     *
     * @param  string $name
     * @param  mixed  $callableOrMethodStr Any callable method
     * @return self
     */
    public function delegate($name, $callableOrMethodStr)
    {
        return $this->injector->delegate($name, $callableOrMethodStr);
    }

    /**
     * Instantiates/provisions a class instance.
     *
     * @param  string $name
     * @param  array  $args
     * @return mixed
     */
    public function make($name, array $args = array())
    {
        return $this->injector->make($name, $args);
    }

    /**
     * Shares the specified class/instance across the Injector context.
     *
     * @param  mixed $nameOrInstance The class or object to share
     * @return self
     */
    public function share($nameOrInstance)
    {
        return $this->injector->share($nameOrInstance);
    }
}
