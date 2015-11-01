<?php

namespace Rougin\Slytherin\IoC;

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
     * Instantiates/provisions a class instance.
     *
     * @param  string $name
     * @param  array  $args
     * @return mixed
     */
    public function make($name, array $args = array());
}
