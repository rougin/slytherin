<?php

namespace Rougin\Slytherin\System\Errors;

use Rougin\Slytherin\System;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class DispatcherNotFound extends NotFound
{
    /**
     * @var string
     */
    protected static $expected = System::DISPATCHER;
}
