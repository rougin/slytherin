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
     * @var \Rougin\Slytherin\Integration\FileLoader
     */
    protected $loader;

    /**
     * @param array<string, mixed>|string|null $data
     */
    public function __construct($data = null)
    {
        $this->loader = new FileLoader;

        if (is_array($data))
        {
            $this->data = $data;
        }

        if (is_string($data))
        {
            $this->data = $this->loader->load($data);
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
        $this->data = $this->loader->load($directory, $this->data);

        return $this->data;
    }

    /**
     * Sets the value to the specified key.
     *
     * @param string  $key
     * @param mixed   $value
     * @param boolean $file
     *
     * @return self
     */
    public function set($key, $value, $file = false)
    {
        $keys = array_filter(explode('.', $key));

        $keys = array_values($keys);

        if ($file)
        {
            /** @phpstan-ignore-next-line */
            $value = $this->loader->loadFile($value);
        }

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
