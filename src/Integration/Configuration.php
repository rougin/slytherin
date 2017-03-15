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
     * @param array $data
     */
    public function __construct(array $data = array())
    {
        $this->data = $data;
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
     * Sets the value to the specified key.
     *
     * @param string $key
     * @param mixed  $value
     * @param mixed
     */
    public function set($key, $value)
    {
        $keys = array_filter(explode('.', $key));

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
            $data[$key] = [];
        }

        return $this->save($keys, $data[$key], $value);
    }
}