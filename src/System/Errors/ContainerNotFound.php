<?php

namespace Rougin\Slytherin\System\Errors;

use Rougin\Slytherin\System;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class ContainerNotFound extends NotFound
{
    /**
     * @var string
     */
    protected static $expected = System::CONTAINER;
}
