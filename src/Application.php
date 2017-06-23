<?php

namespace Rougin\Slytherin;

use Psr\Container\ContainerInterface;
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

    const SERVER_REQUEST = 'Psr\Http\Message\ServerRequestInterface';

    const RESPONSE = 'Psr\Http\Message\ResponseInterface';

    const ROUTE_DISPATCHER = 'Rougin\Slytherin\Routing\DispatcherInterface';

    const ROUTER = 'Rougin\Slytherin\Routing\RouterInterface';

    /**
     * @var \Psr\Container\ContainerInterface
     */
    protected static $container;

    /**
     * @var \Rougin\Slytherin\Integration\Configuration
     */
    protected $configuration;

    /**
     * @param \Psr\Container\ContainerInterface|null $container
     */
    public function __construct(ContainerInterface $container = null, Integration\Configuration $configuration = null)
    {
        $this->configuration = $configuration ?: new Integration\Configuration;

        static::$container = $container ?: new Container\Container;
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
    public function handle(ServerRequestInterface $request)
    {
        $callback = call_user_func_array(array($this, 'callback'), $this->callables());

        if (static::$container->has(self::MIDDLEWARE_DISPATCHER)) {
            $middleware = static::$container->get(self::MIDDLEWARE_DISPATCHER);

            $delegate = new Middleware\Delegate($callback);

            $result = $middleware->process($request, $delegate);
        }

        return (isset($result)) ? $result : $callback($request);
    }

    /**
     * Adds the specified integrations to the container.
     *
     * @param  array|string                                $integrations
     * @param  \Rougin\Slytherin\Integration\Configuration $configuration
     * @return self
     */
    public function integrate($integrations, Integration\Configuration $configuration = null)
    {
        $configuration = $configuration ?: $this->configuration;

        $container = static::container();

        $integrations = (is_string($integrations)) ? array($integrations) : $integrations;

        foreach ($integrations as $integration) {
            $integration = new $integration;

            $container = $integration->define($container, $configuration);
        }

        static::$container = $container;

        $this->configuration = $configuration;

        return $this;
    }

    /**
     * Emits the headers from response and runs the application.
     *
     * @return void
     */
    public function run()
    {
        // NOTE: To be removed in v1.0.0. Use "ErrorHandlerIntegration" instead.
        if (static::$container->has(self::ERROR_HANDLER)) {
            $debugger = static::$container->get(self::ERROR_HANDLER);

            $debugger->display();
        }

        $response = $this->handle(static::$container->get(self::SERVER_REQUEST));

        $code = $response->getStatusCode() . ' ' . $response->getReasonPhrase();

        header('HTTP/' . $response->getProtocolVersion() . ' ' . $code);

        foreach ($response->getHeaders() as $name => $values) {
            header($name . ': ' . implode(',', $values));
        }

        echo (string) $response->getBody();
    }

    /**
     * Returns a listing of callables to be prepared.
     *
     * @return array
     */
    protected function callables()
    {
        $callables = array();

        array_push($callables, $this->dispatch(self::$container));
        array_push($callables, $this->finalize(self::$container));
        array_push($callables, $this->middleware(self::$container));
        array_push($callables, $this->resolve(new Container\Container(array(), self::$container)));

        return $callables;
    }

    /**
     * Returns the result of the function by resolving it through a container.
     *
     * @param  callable $dispatch
     * @param  callable $finalize
     * @param  callable $middleware
     * @param  callable $resolve
     * @return callable
     */
    protected function callback($dispatch, $finalize, $middleware, $resolve)
    {
        return function ($request) use ($dispatch, $finalize, $middleware, $resolve) {
            list($function, $middlewares) = $dispatch($request);

            $callback = function ($request) use ($function, $finalize, $resolve) {
                return $finalize($resolve($function, $request));
            };

            $result = $middleware($callback, $middlewares, $request);

            return $result ?: $callback($request);
        };
    }

    /**
     * Returns the result from \Rougin\Slytherin\Routing\DispatcherInterface.
     *
     * @param  \Psr\Container\ContainerInterface $container
     * @return callable
     */
    protected function dispatch(ContainerInterface $container)
    {
        return function ($request) use ($container) {
            $dispatcher = $container->get('Rougin\Slytherin\Routing\DispatcherInterface');

            if ($container->has('Rougin\Slytherin\Routing\RouterInterface')) {
                $router = $container->get('Rougin\Slytherin\Routing\RouterInterface');

                $dispatcher->router($router);
            }

            list($method, $uri) = array($request->getMethod(), $request->getUri());

            return $dispatcher->dispatch($method, $uri->getPath());
        };
    }

    /**
     * Converts the result into a \Psr\Http\Message\ResponseInterface instance.
     *
     * @param  \Psr\Container\ContainerInterface $container
     * @return callable
     */
    protected function finalize(ContainerInterface $container)
    {
        return function ($result) use ($container) {
            $response = $container->get('Psr\Http\Message\ResponseInterface');

            $response = ($result instanceof ResponseInterface) ? $result : $response;

            $result instanceof ResponseInterface ?: $response->getBody()->write($result);

            return $response;
        };
    }

    /**
     * Dispatches the middlewares of the specified request, if there are any.
     *
     * @param  \Psr\Container\ContainerInterface $container
     * @return callable
     */
    protected function middleware(ContainerInterface $container)
    {
        return function ($callback, $middlewares, $request) use ($container) {
            $result = null;

            if (interface_exists('Interop\Http\ServerMiddleware\MiddlewareInterface')) {
                $response = $container->get('Psr\Http\Message\ResponseInterface');

                $middleware = new Middleware\Dispatcher($middlewares, $response);

                $result = $middleware->process($request, new Middleware\Delegate($callback));
            }

            return $result;
        };
    }

    /**
     * Returns the result of the function by resolving it through a container.
     *
     * @param  \Rougin\Slytherin\Container\Container $container
     * @return callable
     */
    protected function resolve(Container\Container $container)
    {
        return function ($function, $request) use ($container) {
            if (is_array($function) === true) {
                if (is_array($function[0]) && is_string($function[0][0])) {
                    $function[0][0] = $container->resolve($function[0][0], $request);

                    $reflector = new \ReflectionMethod($function[0][0], $function[0][1]);
                } else {
                    $reflector = new \ReflectionFunction($function[0]);
                }

                $function[1] = $container->arguments($reflector, $function[1]);

                $function = call_user_func_array($function[0], $function[1]);
            }

            return $function;
        };
    }
}
