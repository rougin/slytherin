<?php

namespace Rougin\Slytherin\IoC\Auryn;

use Auryn\Injector;

use Rougin\Slytherin\IoC\BaseContainer;
use Rougin\Slytherin\IoC\ContainerInterface;

/**
 * Auryn Container
 *
 * A simple implementation of a container that is built on top of
 * Daniel Lowrey's Auryn Inversion Of Control (IoC) Dependency Injector.
 *
 * https://github.com/rdlowrey/auryn
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Container extends BaseContainer implements ContainerInterface
{
    /**
     * @var \Auryn\Injector
     */
    public $injector;

    /**
     * @param \Auryn\Injector $injector
     */
    public function __construct(Injector $injector)
    {
        $this->injector = $injector;
    }

    /**
     * Adds a new instance to the container.
     *
     * @param string $id
     * @param mixed  $concrete
     */
    public function add($id, $concrete = null)
    {
        if ($concrete && ! is_array($concrete))
        {
            $this->instances[$id] = $concrete;

            try
            {
                $this->share($concrete);
            }
            catch (\Exception $error)
            {}

            try
            {
                $this->alias($id, get_class($concrete));
            }
            catch (\Exception $error)
            {}

            return $this;
        }

        $arguments = [];

        if (is_array($concrete))
        {
            $arguments = $concrete;
        }

        $result = $this->injector->make($id, $arguments);

        $this->instances[$id] = $result;

        return $this;
    }

    public function alias($original, $alias)
    {
        $this->injector->alias($original, $alias);

        if (is_string($alias))
        {
            $alias = $this->injector->make($alias);
        }

        $this->instances[$original] = $alias;

        return $this;
    }

    public function __call($method, $args)
    {
        return call_user_func_array(array($this->injector, $method), $args);
    }
}
