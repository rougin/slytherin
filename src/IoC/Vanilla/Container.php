<?php

namespace Rougin\Slytherin\IoC\Vanilla;

use Rougin\Slytherin\IoC\BaseContainer;

/**
 * Container
 *
 * A container that extends on Rougin\Slytherin\IoC\BaseContainer.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Container extends BaseContainer
{
    /**
     * Adds a new instance to the container.
     *
     * @param string $id
     * @param mixed  $concrete
     */
    public function add($id, $concrete)
    {
        $this->instances[$id] = $concrete;

        return $this;
    }

    /**
     * Creates an alias for a specified class.
     *
     * @param string $alias
     * @param string $original
     */
    public function alias($alias, $original)
    {
        $this->instances[$alias] = $this->get($original);

        return $this;
    }
}
