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
    // NOTE: To be removed in v1.0.0
    const ERROR_HANDLER = 'Rougin\Slytherin\Debug\ErrorHandlerInterface';

    const MIDDLEWARE_DISPATCHER = 'Rougin\Slytherin\Middleware\DispatcherInterface';

    const REQUEST = 'Psr\Http\Message\ServerRequestInterface';

    const RESPONSE = 'Psr\Http\Message\ResponseInterface';

    const ROUTE_DISPATCHER = 'Rougin\Slytherin\Routing\DispatcherInterface';

    const ROUTER = 'Rougin\Slytherin\Routing\RouterInterface';

    /**
     * @var \Rougin\Slytherin\Container\ContainerInterface
     */
    protected static $container;

    /**
     * @param \Rougin\Slytherin\Container\ContainerInterface|null $container
     */
    public function __construct(Container\ContainerInterface $container = null)
    {
        static::$container = (is_null($container)) ? new Container\Container : $container;
    }

    /**
     * Returns the static instance of the specified container.
     *
     * @return \Rougin\Slytherin\Container\ContainerInterface
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
    public function handle(ServerRequestInterface $request)
    {
        static::$container->set(self::REQUEST, $request);

        list($function, $middlewares) = $this->dispatch($request);

        $response = $this->middleware($request, $middlewares);

        $result = $this->resolve($function);

        if (! $result instanceof ResponseInterface) {
            $response->getBody()->write($result);

            $result = $response;
        }

        return $result;
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
     * Returns the result from \Rougin\Slytherin\Routing\DispatcherInterface.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @return array|mixed
     */
    protected function dispatch(ServerRequestInterface $request)
    {
        list($method, $parsed) = array($request->getMethod(), $request->getParsedBody());

        $method = (isset($parsed['_method'])) ? strtoupper($parsed['_method']) : $method;

        $dispatcher = static::$container->get(self::ROUTE_DISPATCHER);

        if (static::$container->has(self::ROUTER)) {
            $router = static::$container->get(self::ROUTER);

            $dispatcher->router($router);
        }

        return $dispatcher->dispatch($method, $request->getUri()->getPath());
    }

    /**
     * Returns a \Psr\Http\Message\ResponseInterface based on given middlewares.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @param  array                                    $middlewares
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function middleware(ServerRequestInterface $request, $middlewares)
    {
        $response = static::$container->get(self::RESPONSE);

        if (static::$container->has(self::MIDDLEWARE_DISPATCHER)) {
            $middleware = static::$container->get(self::MIDDLEWARE_DISPATCHER);

            $middleware->push($middlewares)->push(new Middleware\FinalResponse($response));

            $response = $middleware->process($request, new Middleware\Delegate);
        }

        return $response;
    }

    /**
     * Returns the result of the function by resolving it through a container.
     *
     * @param  array|mixed $function
     * @return mixed
     */
    protected function resolve($function)
    {
        if (is_array($function) === true) {
            list($callback, $parameters) = $function;

            if (is_array($callback) && ! is_object($callback)) {
                list($name, $method) = $callback;

                // NOTE: To be removed in v1.0.0. It should me manually defined.
                $container = new Container\ReflectionContainer(static::$container);

                $callback = array($container->get($name), $method);
            }

            return call_user_func_array($callback, $parameters);
        }

        return $function;
    }
}
