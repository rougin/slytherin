<?php

namespace Rougin\Slytherin\System\Errors;

use Rougin\Slytherin\System;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class RouteNotFound extends NotFound
{
    /**
     * @var string
     */
    protected static $expected = System::ROUTE;
}
