<?php

namespace Rougin\Slytherin\Fixture\Components;

use Rougin\Slytherin\Component\AbstractComponent;
use Rougin\Slytherin\Component\Collection;
use Rougin\Slytherin\Debug\ErrorHandlerInterface;
use Rougin\Slytherin\Debug\Vanilla\Debugger;
use Rougin\Slytherin\System\Errors\DebuggerNotFound;

/**
 * @deprecated since ~0.9, uses deprecated "Debug\Vanilla\Debugger"; use "ErrorHandler" instead.
 *
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

    /**
     * @param \Rougin\Slytherin\Component\Collection $collection
     *
     * @return void
     */
    public function register(Collection $collection)
    {
        $result = $this->get();

        if (! $result instanceof ErrorHandlerInterface)
        {
            throw new DebuggerNotFound($result);
        }

        $collection->setErrorHandler($result);
    }
}
