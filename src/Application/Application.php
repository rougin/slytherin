<?php

namespace Rougin\Slytherin\Application;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Application
 *
 * Integrates all specified components into the application.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Application
{
    const DISPATCHER    = 'Rougin\Slytherin\Routing\DispatcherInterface';
    const ERROR_HANDLER = 'Rougin\Slytherin\Debug\ErrorHandlerInterface'; // NOTE: To be removed in v1.0.0
    const MIDDLEWARE    = 'Rougin\Slytherin\Middleware\MiddlewareInterface';
    const REQUEST       = 'Psr\Http\Message\ServerRequestInterface';
    const RESPONSE      = 'Psr\Http\Message\ResponseInterface';

    /**
     * @var \Interop\Container\ContainerInterface
     */
    protected $container;

    /**
     * @param \Interop\Container\ContainerInterface|null $container
     */
    public function __construct(\Interop\Container\ContainerInterface $container = null)
    {
        if (is_null($container)) {
            $container = new \Rougin\Slytherin\Container\VanillaContainer;
        }

        $this->container = $container;
    }

    /**
     * Handles a ServerRequestInterface to convert it to a ResponseInterface.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request)
    {
        $modifier = new HttpModifier($this->container->get(self::RESPONSE));

        list($function, $middlewares) = $this->dispatch($request);

        $response = null;

        if ($this->container->has(self::MIDDLEWARE)) {
            $modifier->setMiddleware($this->container->get(self::MIDDLEWARE));

            $response = $modifier->invokeMiddleware($request, $middlewares);
        }

        return $modifier->getResponse($this->resolve($function), $response);
    }

    /**
     * Adds the specified integrations to the container.
     *
     * @param  array $integrations
     * @param  array $config
     * @return self
     */
    public function integrate(array $integrations, array $config = array())
    {
        $container = $this->container;

        foreach ($integrations as $integration) {
            $integration = new $integration;

            $container = $integration->define($container, $config);
        }

        $this->container = $container;

        return $this;
    }

    /**
     * Runs the application
     *
     * @return void
     */
    public function run()
    {
        $request = $this->container->get(self::REQUEST);

        // NOTE: To be removed in v1.0.0
        if ($this->container->has(self::ERROR_HANDLER)) {
            $debugger = $this->container->get(self::ERROR_HANDLER);

            $debugger->display();
        }

        echo (string) $this->handle($request)->getBody();
    }

    /**
     * Gets the result from the dispatcher.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @return array
     */
    protected function dispatch(ServerRequestInterface $request)
    {
        $dispatcher = $this->container->get(self::DISPATCHER);

        $method = $request->getMethod();
        $parsed = $request->getParsedBody();

        // For PATCH and DELETE HTTP methods
        $method = (isset($parsed['_method'])) ? strtoupper($parsed['_method']) : $method;
        $route  = $dispatcher->dispatch($method, $request->getUri()->getPath());

        list($function, $parameters, $middlewares) = $route;

        $result = (is_null($parameters)) ? $function : array($function, $parameters);

        return array($result, $middlewares);
    }

    /**
     * Resolves the result based from the dispatched route.
     *
     * @param  array|string $function
     * @return mixed
     */
    protected function resolve($function)
    {
        $result = $function;

        if (is_array($function)) {
            list($class, $parameters) = $function;

            if (is_callable($class) && is_object($class)) {
                return call_user_func_array($class, $parameters);
            }

            list($className, $method) = $class;

            $resolver = new ClassResolver($this->container);

            $result = $resolver->resolve($className);
            $result = call_user_func_array(array($result, $method), $parameters);
        }

        return $result;
    }
}
