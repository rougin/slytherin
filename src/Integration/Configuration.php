<?php

namespace Rougin\Slytherin\Integration;

/**
 * Configuration
 *
 * Serves as a storage for configuration data.
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * @var array<string, mixed>
     */
    protected $data = array();

    /**
     * @param array<string, mixed>|string|null $data
     */
    public function __construct($data = null)
    {
        if (is_array($data)) $this->data = $data;

        if (is_string($data))
        {
            $this->data = $this->load($data);
        }
    }

    /**
     * Returns the value from the specified key.
     *
     * @param  string     $key
     * @param  mixed|null $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        $keys = array_filter(explode('.', $key));

        $data = $this->data;

        for ($i = 0; $i < count($keys); $i++)
        {
            /** @var string */
            $index = $keys[(int) $i];

            $data = &$data[$index];
        }

        return $data !== null ? $data : $default;
    }

    /**
     * Loads the configuration from a specified directory.
     *
     * @param  string $directory
     * @return array<string, mixed>
     */
    public function load($directory)
    {
        /** @var array<int, string> */
        $configurations = glob($directory . '/*.php');

        foreach ($configurations as $item)
        {
            $items = require $item;

            $name = basename($item, '.php');

            $name = array($name => $items);

            $this->data = array_merge($this->data, $name);
        }

        return $this->data;
    }

    /**
     * Sets the value to the specified key.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @param  boolean $fromFile
     * @return self
     */
    public function set($key, $value, $fromFile = false)
    {
        $keys = array_filter(explode('.', $key));

        $value = $fromFile ? require $value : $value;

        $this->save($keys, $this->data, $value);

        return $this;
    }

    /**
     * Saves the specified key in the list of data.
     *
     * @param  array<string, mixed> &$keys
     * @param  array<string, mixed> &$data
     * @param  mixed  $value
     * @return mixed
     */
    protected function save(array &$keys, &$data, $value)
    {
        $key = array_shift($keys);

        if (empty($keys))
        {
            $data[$key] = $value;

            return $data[$key];
        }

        if (! isset($data[$key])) $data[$key] = array();

        return $this->save($keys, $data[$key], $value);
    }
}