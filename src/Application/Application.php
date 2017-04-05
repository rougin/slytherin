<?php

namespace Rougin\Slytherin\Application;

use Rougin\Slytherin\Integration\Configuration;

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
    const ROUTER        = 'Rougin\Slytherin\Routing\RouterInterface';

    /**
     * @var \Psr\Container\ContainerInterface
     */
    protected static $container;

    /**
     * @param \Psr\Container\ContainerInterface|null $container
     */
    public function __construct(\Psr\Container\ContainerInterface $container = null)
    {
        $vanilla = new \Rougin\Slytherin\Container\Container;

        static::$container = (is_null($container)) ? $vanilla : $container;
    }

    /**
     * Returns the static instance of the specified container.
     *
     * @return \Psr\Container\ContainerInterface
     */
    public static function container()
    {
        return static::$container;
    }

    /**
     * Handles a ServerRequestInterface to convert it to a ResponseInterface.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(\Psr\Http\Message\ServerRequestInterface $request)
    {
        $method = $request->getMethod();
        $parsed = $request->getParsedBody();

        // For PATCH and DELETE HTTP methods
        $method = (isset($parsed['_method'])) ? strtoupper($parsed['_method']) : $method;

        list($function, $middlewares) = $this->dispatch($method, $request->getUri()->getPath());

        $modifier = new HttpModifier(static::$container->get(self::RESPONSE));

        $response = null;

        if (static::$container->has(self::MIDDLEWARE)) {
            $modifier->setMiddleware(static::$container->get(self::MIDDLEWARE));

            $response = $modifier->invokeMiddleware($request, $middlewares);
        }

        return $modifier->getResponse($this->resolve($function), $response);
    }

    /**
     * Adds the specified integrations to the container.
     *
     * @param  array                                       $integrations
     * @param  \Rougin\Slytherin\Integration\Configuration $config
     * @return self
     */
    public function integrate(array $integrations, Configuration $config = null)
    {
        $config = ($config == null) ? new Configuration : $config;

        $container = static::container();

        foreach ($integrations as $integration) {
            $integration = new $integration;

            $container = $integration->define($container, $config);
        }

        static::$container = $container;

        return $this;
    }

    /**
     * Runs the application
     *
     * @return void
     */
    public function run()
    {
        $request = static::$container->get(self::REQUEST);

        // NOTE: To be removed in v1.0.0
        if (static::$container->has(self::ERROR_HANDLER)) {
            $debugger = static::$container->get(self::ERROR_HANDLER);

            $debugger->display();
        }

        echo (string) $this->handle($request)->getBody();
    }

    /**
     * Gets the result from the dispatcher.
     *
     * @param  string $method
     * @param  string $path
     * @return array
     */
    protected function dispatch($method, $path)
    {
        $dispatcher = static::$container->get(self::DISPATCHER);

        if (static::$container->has(self::ROUTER)) {
            $router = static::$container->get(self::ROUTER);

            $dispatcher->setRouter($router);
        }

        $route = $dispatcher->dispatch($method, $path);

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

            $resolver = new ClassResolver(static::$container);

            $result = $resolver->resolve($className);
            $result = call_user_func_array(array($result, $method), $parameters);
        }

        return $result;
    }
}
