<?php

namespace Rougin\Slytherin\Test\Fixture\Components;

use Rougin\Slytherin\Debug\Vanilla\Debugger;
use Rougin\Slytherin\Component\AbstractComponent;

/**
 * Debugger Component
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class DebuggerComponent extends AbstractComponent
{
    /**
     * Type of the component:
     * dispatcher, debugger, http, middleware
     * 
     * @var string
     */
    protected $type = 'debugger';

    /**
     * Returns an instance from the named class.
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
