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
     * Sets a new instance to the container.
     *
     * @param  string  $id
     * @param  mixed   $concrete
     * @param  boolean $shared
     * @return self
     */
    public function set($id, $concrete, $shared = false)
    {
        // Backward compatibility on versions >=3.0 ---
        $exists = method_exists($this, 'addShared');

        if ($shared && $exists)
        {
            /** @var callable */
            $class = array($this, 'addShared');

            $params = array($id, $concrete);

            call_user_func_array($class, $params);

            return $this;
        }
        // --------------------------------------------

        $this->add($id, $concrete, $shared);

        return $this;
    }
}