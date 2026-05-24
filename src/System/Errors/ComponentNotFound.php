<?php

namespace Rougin\Slytherin\System\Errors;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class ComponentNotFound extends NotFound
{
    /**
     * @var string
     */
    protected static $expected = \Rougin\Slytherin\System::COMPONENT;
}
