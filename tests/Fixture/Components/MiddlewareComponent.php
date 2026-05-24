<?php

namespace Rougin\Slytherin\Fixture\Components;

use Rougin\Slytherin\Component\AbstractComponent;
use Rougin\Slytherin\Component\Collection;
use Rougin\Slytherin\Middleware\DispatcherInterface;
use Rougin\Slytherin\Middleware\Middleware;
use Rougin\Slytherin\System\Errors\MiddlewareNotFound;

/**
 * Middleware Component
 *
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
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

    /**
     * @param \Rougin\Slytherin\Component\Collection $collection
     *
     * @return void
     */
    public function register(Collection $collection)
    {
        $result = $this->get();

        if (! $result instanceof DispatcherInterface)
        {
            throw new MiddlewareNotFound($result);
        }

        $collection->setMiddleware($result);
    }
}
