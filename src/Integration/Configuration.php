<?php

namespace Rougin\Slytherin\Integration;

/**
 * Configuration
 *
 * Serves as a storage for configuration data.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Configuration
{
    /**
     * @var array
     */
    protected $data = array();

    /**
     * @param array|string|null $data
     */
    public function __construct($data = null)
    {
        $this->data = (is_array($data)) ? $data : $this->data;
        $this->data = (is_string($data)) ? $this->load($data) : $this->data;
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

        $length = count($keys);

        $data = $this->data;

        for ($i = 0; $i < $length; $i++) {
            $index = $keys[$i];

            $data = &$data[$index];
        }

        return (empty($data)) ? $default : $data;
    }

    /**
     * Loads the configuration from a specified directory.
     *
     * @param  string $directory
     * @return self
     */
    public function load($directory)
    {
        $configurations = glob($directory . '/*.php');

        foreach ($configurations as $configuration) {
            $items = require $configuration;
            $name  = basename($configuration, '.php');

            $this->data = array_merge($this->data, array($name => $items));
        }

        return $this->data;
    }

    /**
     * Sets the value to the specified key.
     *
     * @param string  $key
     * @param mixed   $value
     * @param boolean $fromFile
     * @param mixed
     */
    public function set($key, $value, $fromFile = false)
    {
        $keys  = array_filter(explode('.', $key));
        $value = ($fromFile) ? require $value : $value;

        return $this->save($keys, $this->data, $value);
    }

    /**
     * Saves the specified key in the list of data.
     *
     * @param  array  &$keys
     * @param  array  &$data
     * @param  mixed  $value
     * @return mixed
     */
    protected function save(array &$keys, &$data, $value)
    {
        $key = array_shift($keys);

        if (empty($keys)) {
            $data[$key] = $value;

            return $data[$key];
        }

        if (! isset($data[$key])) {
            $data[$key] = array();
        }

        return $this->save($keys, $data[$key], $value);
    }
}
