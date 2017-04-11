<?php

namespace Rougin\Slytherin;

use Psr\Http\Message\ResponseInterface;
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
    const DISPATCHER = 'Rougin\Slytherin\Routing\DispatcherInterface';

    // NOTE: To be removed in v1.0.0
    const ERROR_HANDLER = 'Rougin\Slytherin\Debug\ErrorHandlerInterface';

    const MIDDLEWARE = 'Rougin\Slytherin\Middleware\MiddlewareInterface';

    const REQUEST = 'Psr\Http\Message\ServerRequestInterface';

    const RESPONSE = 'Psr\Http\Message\ResponseInterface';

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
        $dispatcher = static::$container->get(self::DISPATCHER);

        if (static::$container->has(self::ROUTER)) {
            $router = static::$container->get(self::ROUTER);

            $dispatcher->router($router);
        }

        list($function, $parameters, $middlewares) = $dispatcher->dispatch($method, $path);

        $result = (is_null($parameters)) ? $function : array($function, $parameters);

        $function = array($result, $middlewares);

        return ($resolve && is_array($result)) ? $this->resolve($result) : $function;
    }

    /**
     * Handles the ServerRequestInterface to convert it to a ResponseInterface.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request)
    {
        list($method, $parsed) = array($request->getMethod(), $request->getParsedBody());

        // For PATCH and DELETE HTTP methods in forms
        $method = (isset($parsed['_method'])) ? strtoupper($parsed['_method']) : $method;

        list($function, $middlewares) = $this->dispatch($method, $request->getUri()->getPath());

        $response = static::$container->get(self::RESPONSE);

        if (static::$container->has(self::MIDDLEWARE)) {
            $middleware = static::$container->get(self::MIDDLEWARE);

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
        $configuration = $configuration ?: new Configuration;

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
     * @param  \Psr\Http\Message\ResponseInterface       $response
     * @param  \Psr\Http\Message\ResponseInterface|mixed $result
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function convert($response, $result)
    {
        $headers = $response->getHeaders();

        if ($result instanceof ResponseInterface) {
            $headers = array_merge($headers, $result->getHeaders());

            $response = $response->withBody($result->getBody());
        } else {
            $response->getBody()->write($result);
        }

        $code = $response->getStatusCode() . ' ' . $response->getReasonPhrase();

        header('HTTP/' . $response->getProtocolVersion() . ' ' . $code);

        foreach (array_unique($headers, SORT_REGULAR) as $name => $value) {
            header($name . ': ' . implode(',', $value));
        }

        return $response;
    }

    /**
     * Returns the result of the result by resolving it through a container.
     *
     * @param  array $result
     * @return mixed
     */
    protected function resolve($result)
    {
        list($class, $parameters) = $result;

        if (is_array($class) && ! is_object($class)) {
            list($name, $method) = $class;

            // NOTE: To be removed in v1.0.0. It should me manually defined.
            $container = new Container\ReflectionContainer(static::$container);

            $class = array($container->get($name), $method);
        }

        return call_user_func_array($class, $parameters);
    }
}
