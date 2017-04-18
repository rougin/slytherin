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
     * Handles the ServerRequestInterface to convert it to a ResponseInterface.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(\Psr\Http\Message\ServerRequestInterface $request)
    {
        list($method, $parsed) = array($request->getMethod(), $request->getParsedBody());

        $method = (isset($parsed['_method'])) ? strtoupper($parsed['_method']) : $method;

        list($function, $middlewares) = $this->dispatch($method, $request->getUri()->getPath());

        $response = static::$container->get(self::RESPONSE);

        if (static::$container->has(self::MIDDLEWARE_DISPATCHER)) {
            $middleware = static::$container->get(self::MIDDLEWARE_DISPATCHER);

            $middleware->push($middlewares);

            $response = $middleware->process($request, new Middleware\Delegate);
        }

        return $this->convert($response, $this->resolve($function));
    }

    /**
     * Adds the specified integrations to the container.
     *
     * @param  array                                       $integrations
     * @param  \Rougin\Slytherin\Integration\Configuration $configuration
     * @return self
     */
    public function integrate(array $integrations, Integration\Configuration $configuration = null)
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
     * Emits the headers from response and runs the application.
     *
     * @return void
     */
    public function run()
    {
        // NOTE: To be removed in v1.0.0
        if (static::$container->has(self::ERROR_HANDLER)) {
            $debugger = static::$container->get(self::ERROR_HANDLER);

            $debugger->display();
        }

        $response = $this->handle(static::$container->get(self::REQUEST));

        $code = $response->getStatusCode() . ' ' . $response->getReasonPhrase();

        header('HTTP/' . $response->getProtocolVersion() . ' ' . $code);

        foreach ($response->getHeaders() as $name => $values) {
            header($name . ': ' . implode(',', $values));
        }

        echo (string) $response->getBody();
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
        if (! $result instanceof \Psr\Http\Message\ResponseInterface) {
            $result = (string) $result;

            $response->getBody() != '' || $response->getBody()->write($result);

            return $response;
        }

        $headers = $result->getHeaders();

        array_walk($headers, function ($values, $name) use (&$response) {
            $response = $response->withHeader($name, $values);
        });

        return $response->withBody($result->getBody());
    }

    /**
     * Returns the result from \Rougin\Slytherin\Routing\DispatcherInterface.
     *
     * @param  string  $method
     * @param  string  $path
     * @return array|mixed
     */
    protected function dispatch($method, $path)
    {
        $dispatcher = static::$container->get(self::ROUTE_DISPATCHER);

        if (static::$container->has(self::ROUTER)) {
            $router = static::$container->get(self::ROUTER);

            $dispatcher->router($router);
        }

        return $dispatcher->dispatch($method, $path);
    }

    /**
     * Returns the result of the function by resolving it through a container.
     *
     * @param  array|mixed $function
     * @return mixed
     */
    protected function resolve($function)
    {
        if (! is_array($function)) return $function;

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
