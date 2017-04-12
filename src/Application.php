<?php

namespace Rougin\Slytherin;

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
    // NOTE: To be removed in v1.0.0
    const ERROR_HANDLER = 'Rougin\Slytherin\Debug\ErrorHandlerInterface';

    const MIDDLEWARE_DISPATCHER = 'Rougin\Slytherin\Middleware\DispatcherInterface';

    const REQUEST = 'Psr\Http\Message\ServerRequestInterface';

    const RESPONSE = 'Psr\Http\Message\ResponseInterface';

    const ROUTE_DISPATCHER = 'Rougin\Slytherin\Routing\DispatcherInterface';

    const ROUTER = 'Rougin\Slytherin\Routing\RouterInterface';

    /**
     * @var \Psr\Container\ContainerInterface
     */
    protected static $container;

    /**
     * @param \Psr\Container\ContainerInterface|null $container
     */
    public function __construct(\Psr\Container\ContainerInterface $container = null)
    {
        $vanilla = new Container\Container;

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
     * Returns the result from \Rougin\Slytherin\Routing\DispatcherInterface.
     *
     * @param  string  $method
     * @param  string  $path
     * @param  boolean $resolve
     * @return array|mixed
     */
    public function dispatch($method, $path, $resolve = false)
    {
        $dispatcher = static::$container->get(self::ROUTE_DISPATCHER);

        if (static::$container->has(self::ROUTER)) {
            $router = static::$container->get(self::ROUTER);

            $dispatcher->router($router);
        }

        $result = $dispatcher->dispatch($method, $path);

        list($function) = $result;

        $solvable = $resolve && is_array($function);

        return ($solvable) ? $this->resolve($function) : $result;
    }

    /**
     * Handles the ServerRequestInterface to convert it to a ResponseInterface.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(\Psr\Http\Message\ServerRequestInterface $request)
    {
        list($method, $parsed) = array($request->getMethod(), $request->getParsedBody());

        // For PATCH and DELETE HTTP methods in forms
        $method = (isset($parsed['_method'])) ? strtoupper($parsed['_method']) : $method;

        list($function, $middlewares) = $this->dispatch($method, $request->getUri()->getPath());

        $response = static::$container->get(self::RESPONSE);

        if (static::$container->has(self::MIDDLEWARE_DISPATCHER)) {
            $middleware = static::$container->get(self::MIDDLEWARE_DISPATCHER);

            $response = $middleware($request, $response, $middleware->stack($middlewares));
        }

        $result = (is_array($function)) ? $this->resolve($function) : $function;

        return $this->convert($response, $result);
    }

    /**
     * Adds the specified integrations to the container.
     *
     * @param  array                                       $integrations
     * @param  \Rougin\Slytherin\Integration\Configuration $configuration
     * @return self
     */
    public function integrate(array $integrations, Configuration $configuration = null)
    {
        $configuration = $configuration ?: new Integration\Configuration;

        $container = static::container();

        foreach ($integrations as $integration) {
            $integration = new $integration;

            $container = $integration->define($container, $configuration);
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
     * Converts the result into \Psr\Http\Message\ResponseInterface.
     *
     * @param  \Psr\Http\Message\ResponseInterface        $response
     * @param  \Psr\Http\Message\ResponseInterface|string $result
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function convert($response, $result)
    {
        $headers = $response->getHeaders();

        if ($result instanceof \Psr\Http\Message\ResponseInterface) {
            $headers = array_merge($headers, $result->getHeaders());

            $response = $response->withBody($result->getBody());
        } else {
            $response->getBody()->write((string) $result);
        }

        $code = $response->getStatusCode() . ' ' . $response->getReasonPhrase();

        header('HTTP/' . $response->getProtocolVersion() . ' ' . $code);

        foreach (array_unique($headers, SORT_REGULAR) as $name => $value) {
            header($name . ': ' . implode(',', $value));
        }

        return $response;
    }

    /**
     * Returns the result of the function by resolving it through a container.
     *
     * @param  array $function
     * @return mixed
     */
    protected function resolve($function)
    {
        list($callback, $parameters) = $function;

        if (is_array($callback) && ! is_object($callback)) {
            list($name, $method) = $callback;

            // NOTE: To be removed in v1.0.0. It should me manually defined.
            $container = new Container\ReflectionContainer(static::$container);

            $callback = array($container->get($name), $method);
        }

        return call_user_func_array($callback, $parameters);
    }
}
