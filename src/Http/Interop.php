<?php

namespace Rougin\Slytherin\Http;

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
        return 'Psr\Http\Message\MessageInterface';
    }

    /**
     * @return string
     */
    protected static function getMethod()
    {
        return 'getProtocolVersion';
    }
}
