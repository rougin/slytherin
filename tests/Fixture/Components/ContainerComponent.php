<?php

namespace Rougin\Slytherin\Test\Fixture\Components;

use Rougin\Slytherin\Component\AbstractComponent;
use Rougin\Slytherin\IoC\Container;

/**
 * Container Component
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class ContainerComponent extends AbstractComponent
{
    /**
     * Type of the component:
     * container, dispatcher, debugger, http, middleware, template
     * 
     * @var string
     */
    protected $type = 'container';

    /**
     * Returns an instance from the named class.
     * 
     * @return mixed
     */
    public function get()
    {
        return new Container;
    }
}
