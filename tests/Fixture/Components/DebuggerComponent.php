<?php

namespace Rougin\Slytherin\Test\Fixture\Components;

/**
 * Debugger Component
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class DebuggerComponent extends \Rougin\Slytherin\Component\AbstractComponent
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
     * It's used in supporting component types for Slytherin.
     *
     * @return mixed
     */
    public function get()
    {
        $debugger = new \Rougin\Slytherin\Debug\Vanilla\Debugger;

        $debugger->setEnvironment('development');

        return $debugger;
    }
}
