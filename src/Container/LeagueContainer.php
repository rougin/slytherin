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
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     *
     * @param  string $id
     * @return mixed
     */
    public function get($id)
    {
        return parent::get($id);
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
     * @param  string     $id
     * @param  mixed|null $concrete
     * @param  boolean    $share
     * @return self
     */
    public function set($id, $concrete = null, $share = false)
    {
        return $this->add($id, $concrete, $share);
    }
}
