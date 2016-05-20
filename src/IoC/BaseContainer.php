<?php

namespace Rougin\Slytherin\IoC;

use Interop\Container\ContainerInterface as InteropContainerInterface;

use Rougin\Slytherin\IoC\Exception\NotFoundException;

/**
 * Base Container
 *
 * A simple implementation of a container that is based on
 * Interop\Container\ContainerInterface.
 * 
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class BaseContainer implements InteropContainerInterface
{
    /**
     * @var array
     */
    public $instances = [];

    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @throws NotFoundException  No entry was found for this identifier.
     * @throws ContainerException Error while retrieving the entry.
     *
     * @return mixed Entry.
     */
    public function get($id)
    {
        if ( ! $this->has($id)) {
            $message = 'Alias (%s) is not being managed by the container';

            throw new NotFoundException(sprintf($message, $id));
        }

        return $this->instances[$id];
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * @param  string $id Identifier of the entry to look for.
     * @return boolean
     */
    public function has($id)
    {
        return isset($this->instances[$id]);
    }
}