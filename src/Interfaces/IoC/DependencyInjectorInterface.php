<?php

namespace Rougin\Slytherin\Interfaces\IoC;

/**
 * Dependency Injector Interface
 *
 * An interface for handling third party dependency injectors
 * 
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
interface DependencyInjectorInterface
{
    /**
     * Instantiate/provision a class instance
     *
     * @param  string $name
     * @param  array  $args
     * @return mixed
     */
    public function make($name, array $args = array());
}
