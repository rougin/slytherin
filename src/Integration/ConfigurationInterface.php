<?php

namespace Rougin\Slytherin\Integration;

/**
 * Configuration Interface
 *
 * An interface for handling configurations.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
interface ConfigurationInterface
{
    /**
     * Returns the value from the specified key.
     *
     * @param  string     $key
     * @param  mixed|null $default
     * @return mixed
     */
    public function get($key, $default = null);
}
