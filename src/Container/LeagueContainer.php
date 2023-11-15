<?php

namespace Rougin\Slytherin\Container;

use League\Container\Container as BaseContainer;

/**
 * League Container
 *
 * A simple implementation of a container that is based on League\Container.
 *
 * http://container.thephpleague.com
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class LeagueContainer extends BaseContainer implements ContainerInterface
{
    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     *
     * @param  string $id
     * @param  array  $arguments
     * @return mixed
     */
    public function get($id, array $arguments = array())
    {
        return parent::get($id, $arguments);
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     *
     * @param  string $id
     * @return boolean
     */
    public function has($id)
    {
        return parent::has($id);
    }

    /**
     * Sets a new instance to the container.
     *
     * @param  string  $id
     * @param  mixed   $concrete
     * @param  boolean $share
     * @return self
     */
    public function set($id, $concrete, $share = false)
    {
        return $this->add($id, $concrete, $share);
    }
}
