<?php

namespace Rougin\Slytherin\Container;

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
class AurynContainer extends Container
{
    /**
     * @var \Auryn\Injector
     */
    public $injector;

    /**
     * @param \Auryn\Injector $injector
     */
    public function __construct(\Auryn\Injector $injector)
    {
        $this->injector = $injector;
    }

    /**
     * Sets a new instance to the container.
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     *
     * @param  string     $id
     * @param  mixed|null $concrete
     * @return self
     */
    public function set($id, $concrete = null)
    {
        try {
            $arguments = (is_array($concrete)) ? $concrete : array();

            $this->instances[$id] = $this->injector->make($id, $arguments);
        } catch (\Exception $e) {
            $message = 'Unable to get alias (%s)';

            throw new Exception\ContainerException(sprintf($message, $id), 0, $e);
        }

        return $this;
    }
}
