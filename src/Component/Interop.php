<?php

namespace Rougin\Slytherin\Component;

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
    protected static function psrClass()
    {
        return 'Psr\Container\ContainerInterface';
    }

    /**
     * @return string
     */
    protected static function psrMethod()
    {
        return 'has';
    }
}
