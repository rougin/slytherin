<?php

namespace Rougin\Slytherin\Container\V1;

use League\Container\Container as League;
use Rougin\Slytherin\Container\ContainerInterface;

/**
 * A simple implementation of a container that is based on League\Container.
 *
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 *
 * @link https://container.thephpleague.com
 */
class LeagueContainer extends League implements ContainerInterface
{
    /**
     * Sets a new instance to the container.
     *
     * @param string  $id
     * @param mixed   $concrete
     * @param boolean $shared
     *
     * @return self
     */
    public function set($id, $concrete, $shared = false)
    {
        // Added $shared for backward compatibility ---
        /** @phpstan-ignore-next-line */
        $this->add($id, $concrete, $shared);
        // --------------------------------------------

        return $this;
    }
}
