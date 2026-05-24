<?php

namespace Rougin\Slytherin\System\Errors;

use Rougin\Slytherin\System;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class RequestNotFound extends NotFound
{
    /**
     * @var string
     */
    protected static $expected = System::REQUEST;
}
