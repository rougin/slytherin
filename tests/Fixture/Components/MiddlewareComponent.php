<?php

namespace Rougin\Slytherin\Test\Fixture\Components;

use Zend\Stratigility\MiddlewarePipe;

use Rougin\Slytherin\Component\AbstractComponent;
use Rougin\Slytherin\Middleware\Stratigility\Middleware;

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
     * 
     * @return mixed
     */
    public function get()
    {
        return new Middleware(new MiddlewarePipe);
    }
}
