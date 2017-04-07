<?php

namespace Rougin\Slytherin\Container;

/**
 * League Container
 *
 * A simple implementation of a container that is based on League\Container.
 *
 * http://container.thephpleague.com
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class LeagueContainer extends \League\Container\Container implements ContainerInterface
{
    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param  string $alias
     * @return mixed
     */
    public function get($alias)
    {
        return parent::get($alias);
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     *
     * @param  string $alias
     * @return boolean
     */
    public function has($alias)
    {
        return parent::has($alias);
    }

    /**
     * Sets a new instance to the container.
     *
     * @param  string     $alias
     * @param  mixed|null $concrete
     * @param  boolean    $share
     * @return self
     */
    public function set($alias, $concrete = null, $share = false)
    {
        return $this->add($alias, $concrete, $share);
    }
}
