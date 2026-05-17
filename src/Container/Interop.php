<?php

namespace Rougin\Slytherin\Container;

use Rougin\Slytherin\Interop as Slytherin;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Interop extends Slytherin
{
    /**
     * @return string
     */
    protected static function getClass()
    {
        return 'Psr\Container\ContainerInterface';
    }

    /**
     * @return string
     */
    protected static function getMethod()
    {
        return 'has';
    }
}
