<?php

namespace Rougin\Slytherin\System\Errors;

use Rougin\Slytherin\Container\NotFoundException;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class ComponentNotArray extends NotFoundException
{
    public function __construct()
    {
        parent::__construct('Component is not an array');
    }
}
