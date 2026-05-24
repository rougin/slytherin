<?php

namespace Rougin\Slytherin\Fixture\Components;

use Rougin\Slytherin\Component\AbstractComponent;
use Rougin\Slytherin\Component\Collection;
use Rougin\Slytherin\Dispatching\Vanilla\Router;
use Rougin\Slytherin\Dispatching\Vanilla\Dispatcher;
use Rougin\Slytherin\Routing\DispatcherInterface;
use Rougin\Slytherin\System\Errors\DispatcherNotFound;

/**
 * @deprecated since ~0.9, uses deprecated "Dispatching\Vanilla\Router" and "Dispatching\Vanilla\Dispatcher".
 *
 * Dispatcher Component
 *
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class DispatcherComponent extends AbstractComponent
{
    /**
     * Type of the component:
     * container, dispatcher, debugger, http, middleware, template
     *
     * @var string
     */
    protected $type = 'dispatcher';

    /**
     * Returns an instance from the named class.
     * It's used in supporting component types for Slytherin.
     *
     * @return mixed
     */
    public function get()
    {
        $routes = array();

        $last = 'Rougin\Slytherin\Fixture\Middlewares\LastMiddleware';

        $routes[] = array('GET', '/', array('Rougin\Slytherin\Fixture\Classes\NewClass', 'index'));
        $routes[] = array('GET', '/optional', array('Rougin\Slytherin\Fixture\Classes\WithOptionalParameter', 'index'));
        $routes[] = array('GET', '/parameter', array('Rougin\Slytherin\Fixture\Classes\WithParameter', 'index'));
        $routes[] = array('GET', '/hello', array('Rougin\Slytherin\Fixture\Classes\WithResponseInterface', 'index'));
        $routes[] = array('GET', '/error', array('Rougin\Slytherin\Fixture\Classes\WithResponseInterface', 'error'));
        $routes[] = array('GET', '/middleware', array('Rougin\Slytherin\Fixture\Classes\NewClass', 'index'), $last);
        $routes[] = array('PUT', '/hello', array('Rougin\Slytherin\Fixture\Classes\WithPutHttpMethod', 'index'));
        $routes[] = array('GET', '/callback', function ()
        {
            return 'Hello';
        });

        return new Dispatcher(new Router($routes));
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
            throw new DispatcherNotFound($result);
        }

        $collection->setDispatcher($result);
    }
}
