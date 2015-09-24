<?php

namespace Rougin\Slytherin\Installation;

/**
 * File Class
 *
 * A simple class handler for manipulating files
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class File
{
    /**
     * Creates a file from a specified template
     * 
     * @param  string  $file
     * @param  string  $template
     * @return boolean
     */
    public static function create($file, $template)
    {
        if (file_exists($file)) {
            return FALSE;
        }

        $content = file_get_contents($template);

        $file = fopen($file, 'wb');
        file_put_contents($file, $content);

        return fclose($file);
    }
}