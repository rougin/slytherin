<?php

namespace Rougin\Slytherin\Test\Fixture\Components;

use Whoops\Run;

use Rougin\Slytherin\Debug\Whoops\Debugger;
use Rougin\Slytherin\Component\AbstractComponent;

/**
 * Debugger Component
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class DebuggerComponent extends AbstractComponent
{
    /**
     * Type of the component:
     * container, dispatcher, debugger, http, middleware, template
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
        $debugger = new Debugger(new Run);

        $debugger->setEnvironment('development');

        return $debugger;
    }
}
