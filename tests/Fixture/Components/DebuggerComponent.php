<?php

namespace Rougin\Slytherin\Fixture\Components;

use Rougin\Slytherin\Component\AbstractComponent;
use Rougin\Slytherin\Debug\Vanilla\Debugger;

/**
 * Debugger Component
 *
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class DebuggerComponent extends AbstractComponent
{
    /**
     * Type of the component:
     * container, dispatcher, debugger, http, middleware, template
     *
     * @var string
     */
    protected $type = 'error_handler';

    /**
     * Returns an instance from the named class.
     * It's used in supporting component types for Slytherin.
     *
     * @return mixed
     */
    public function get()
    {
        $debugger = new Debugger;

        $debugger->setEnvironment('development');

        return $debugger;
    }
}
