<?php

namespace Rougin\Slytherin\Fixture\Components;

use Rougin\Slytherin\Component\AbstractComponent;
use Rougin\Slytherin\Middleware\Middleware;

/**
 * Middleware Component
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class MiddlewareComponent extends AbstractComponent
{
    /**
     * Type of the component:
     * container, dispatcher, debugger, http, middleware, template
     *
     * @var string
     */
    protected $type = 'middleware';

    /**
     * Returns an instance from the named class.
     * It's used in supporting component types for Slytherin.
     *
     * @return mixed
     */
    public function get()
    {
        return new Middleware;
    }
}
