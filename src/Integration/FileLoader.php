<?php

namespace Rougin\Slytherin\Integration;

/**
 * Loads configuration data from files.
 *
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class FileLoader
{
    /**
     * Loads configuration from the specified directory.
     *
     * @param string               $directory
     * @param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    public function load($directory, $data = array())
    {
        $items = glob($directory . '/*.php');

        $items = is_array($items) ? $items : array();

        foreach ($items as $item)
        {
            $name = basename($item, '.php');

            $loaded = require $item;

            $name = array($name => $loaded);

            $data = array_merge($data, $name);
        }

        return $data;
    }

    /**
     * Loads configuration from the specified file.
     *
     * @param string $file
     *
     * @return mixed
     */
    public function loadFile($file)
    {
        return require $file;
    }
}
