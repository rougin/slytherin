<?php

namespace Rougin\Slytherin\Integration;

/**
 * Serves as a storage for configuration data.
 *
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Configuration
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
        if (is_array($data))
        {
            $this->data = $data;
        }

        if (is_string($data))
        {
            $this->data = $this->load($data);
        }
    }

    /**
     * Returns the value from the specified key.
     *
     * @param string     $key
     * @param mixed|null $default
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        $data = $this->data;

        $keys = array_filter(explode('.', $key));

        $keys = array_values($keys);

        for ($i = 0; $i < count($keys); $i++)
        {
            $index = $keys[$i];

            /** @phpstan-ignore-next-line */
            $data = &$data[$index];
        }

        return $data !== null ? $data : $default;
    }

    /**
     * Loads the configuration from a specified
     * directory.
     *
     * @param string $directory
     *
     * @return array<string, mixed>
     */
    public function load($directory)
    {
        $items = glob($directory . '/*.php');

        $items = is_array($items) ? $items : array();

        foreach ($items as $item)
        {
            $name = basename($item, '.php');

            $items = require $item;

            $name = array($name => $items);

            $data = array_merge($this->data, $name);

            $this->data = $data;
        }

        return $this->data;
    }

    /**
     * Sets the value to the specified key.
     *
     * @param string  $key
     * @param mixed   $value
     * @param boolean $fromFile
     *
     * @return self
     */
    public function set($key, $value, $fromFile = false)
    {
        $keys = array_filter(explode('.', $key));

        $keys = array_values($keys);

        $value = $fromFile ? require $value : $value;

        $this->save($keys, $this->data, $value);

        return $this;
    }

    /**
     * Saves the specified key in the list of data.
     *
     * @param string[]             &$keys
     * @param array<string, mixed> &$data
     * @param mixed                $value
     *
     * @return mixed
     */
    protected function save(array &$keys, &$data, $value)
    {
        $key = array_shift($keys);

        if ($key === null)
        {
            return;
        }

        if (empty($keys))
        {
            $data[$key] = $value;

            return $data[$key];
        }

        if (! isset($data[$key]))
        {
            $data[$key] = array();
        }

        /** @phpstan-ignore argument.type */
        return $this->save($keys, $data[$key], $value);
    }
}
