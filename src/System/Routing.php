<?php

namespace Rougin\Slytherin\System;

use Rougin\Slytherin\Http\HttpIntegration;
use Rougin\Slytherin\Routing\Router;
use Rougin\Slytherin\Routing\RoutingIntegration;
use Rougin\Slytherin\System;

/**
 * Routing
 *
 * A routing utility for defining HTTP routes directly.
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Routing extends System
{
    /**
     * @var \Rougin\Slytherin\Routing\RouterInterface|null
     */
    protected $router = null;

    /**
     * Adds a new raw route.
     *
     * @param  string                                                      $method
     * @param  string                                                      $uri
     * @param  callable|string[]|string                                    $handler
     * @param  \Rougin\Slytherin\Middleware\MiddlewareInterface[]|string[] $middlewares
     * @return self
     */
    public function add($method, $uri, $handler, $middlewares = array())
    {
        if (is_null($this->router))
        {
            $this->router = new Router;
        }

        $this->router->add($method, $uri, $handler, $middlewares);

        return $this;
    }

    /**
     * Emits the headers from response and runs the application.
     *
     * @return void
     */
    public function run()
    {
        if (is_null($this->router))
        {
            parent::run(); return;
        }

        // Prepare the HttpIntegration -------------------
        $this->config->set('app.http.cookies', $_COOKIE);

        $this->config->set('app.http.files', $_FILES);

        $this->config->set('app.http.get', (array) $_GET);

        $this->config->set('app.http.post', $_POST);

        $this->config->set('app.http.server', $_SERVER);

        $items = array(new HttpIntegration);
        // -----------------------------------------------

        // Prepare the RoutingIntegration -------------------
        $items[] = new RoutingIntegration;

        $this->integrate($items);

        $this->container->set(System::ROUTER, $this->router);
        // --------------------------------------------------

        parent::run(); return;
    }

    /**
     * Calls methods from the Router instance.
     *
     * @param  string  $method
     * @param  mixed[] $params
     * @return mixed
     */
    public function __call($method, $params)
    {
        array_unshift($params, $method);

        /** @var callable $class */
        $class = array($this, 'add');

        return call_user_func_array($class, $params);
    }
}
