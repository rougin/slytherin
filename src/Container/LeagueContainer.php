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
