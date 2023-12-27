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
 *
 * @method add($method, $uri, $handler, $middlewares = array())
 * @method delete($uri, $handler, $middlewares = array())
 * @method get($uri, $handler, $middlewares = array())
 * @method merge(array $routes)
 * @method parsed(array $routes = array())
 * @method patch($uri, $handler, $middlewares = array())
 * @method post($uri, $handler, $middlewares = array())
 * @method prefix($prefix = '', $namespace = null)
 * @method put($uri, $handler, $middlewares = array())
 * @method restful($uri, $class, $middlewares = array())
 * @method routes()
 */
class Routing extends System
{
    /**
     * @var \Rougin\Slytherin\Routing\RouterInterface|null
     */
    protected $router = null;

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
        if (! $this->router) $this->router = new Router;

        /** @var callable $class */
        $class = array($this->router, $method);

        return call_user_func_array($class, $params);
    }
}
