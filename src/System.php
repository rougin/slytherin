<?php

namespace Rougin\Slytherin;

use Psr\Http\Message\ServerRequestInterface;
use Rougin\Slytherin\Container\Container;
use Rougin\Slytherin\Debug\ErrorHandlerInterface;
use Rougin\Slytherin\Integration\Configuration;
use Rougin\Slytherin\Integration\IntegrationInterface;
use Rougin\Slytherin\Middleware\DispatcherInterface as MiddlewareInterface;
use Rougin\Slytherin\Routing\DispatcherInterface as RoutingInterface;
use Rougin\Slytherin\Routing\RouterInterface;
use Rougin\Slytherin\System\Errors\DebuggerNotFound;
use Rougin\Slytherin\System\Errors\DispatcherNotFound;
use Rougin\Slytherin\System\Errors\IntegrationNotFound;
use Rougin\Slytherin\System\Errors\MiddlewareNotFound;
use Rougin\Slytherin\System\Errors\RequestNotFound;
use Rougin\Slytherin\System\Errors\RouterNotFound;
use Rougin\Slytherin\System\Handler;

/**
 * Integrates all specified components for Slytherin.
 *
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class System
{
    const CONFIG = 'Rougin\Slytherin\Integration\Configuration';

    const COMPONENT = 'Rougin\Slytherin\Component\ComponentInterface';

    const CONTAINER = 'Rougin\Slytherin\Container\ContainerInterface';

    const DEBUGGER = 'Rougin\Slytherin\Debug\ErrorHandlerInterface';

    const DISPATCHER = 'Rougin\Slytherin\Routing\DispatcherInterface';

    const INTEGRATION = 'Rougin\Slytherin\Integration\IntegrationInterface';

    const MIDDLEWARE = 'Rougin\Slytherin\Middleware\DispatcherInterface';

    const REQUEST = 'Psr\Http\Message\ServerRequestInterface';

    const RESPONSE = 'Psr\Http\Message\ResponseInterface';

    const ROUTE = 'Rougin\Slytherin\Routing\RouteInterface';

    const ROUTER = 'Rougin\Slytherin\Routing\RouterInterface';

    const TEMPLATE = 'Rougin\Slytherin\Template\RendererInterface';

    /**
     * @var \Rougin\Slytherin\Integration\Configuration
     */
    protected $config;

    /**
     * @var \Rougin\Slytherin\Container\ContainerInterface
     */
    protected $container;

    /**
     * Initializes the application instance.
     *
     * @param \Rougin\Slytherin\Container\ContainerInterface|null $container
     * @param \Rougin\Slytherin\Integration\Configuration|null    $config
     *
     * @todo Remove usage of "null" in this method.
     */
    public function __construct($container = null, $config = null)
    {
        if (! $config)
        {
            $config = new Configuration;
        }

        $this->config = $config;

        if (! $container)
        {
            $container = new Container;
        }

        $this->container = $container;
    }

    /**
     * Handles the ServerRequestInterface to convert
     * it to a ResponseInterface.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request)
    {
        $uri = $request->getUri()->getPath();

        $method = $request->getMethod();

        $dispatcher = $this->container->get(self::DISPATCHER);

        if (! $dispatcher instanceof RoutingInterface)
        {
            throw new DispatcherNotFound($dispatcher);
        }

        if ($this->container->has(self::ROUTER))
        {
            $router = $this->container->get(self::ROUTER);

            if (! $router instanceof RouterInterface)
            {
                throw new RouterNotFound($router);
            }

            $dispatcher = $dispatcher->setRouter($router);
        }

        $route = $dispatcher->dispatch($method, $uri);

        $items = $route->getMiddlewares();

        $handler = new Handler($route, $this->container);

        if (! $this->container->has(self::MIDDLEWARE))
        {
            return $handler->handle($request);
        }

        $middleware = $this->container->get(self::MIDDLEWARE);

        if (! $middleware instanceof MiddlewareInterface)
        {
            throw new MiddlewareNotFound($middleware);
        }

        $stack = $middleware->getStack();

        $middleware->setStack(array_merge($items, $stack));

        return $middleware->process($request, $handler);
    }

    /**
     * Adds the specified integrations to the container.
     *
     * @param mixed|mixed[]                                    $items
     * @param \Rougin\Slytherin\Integration\Configuration|null $config
     *
     * @return self
     *
     * @todo Remove usage of "null" in this method.
     */
    public function integrate($items, $config = null)
    {
        if (! $config)
        {
            $config = $this->config;
        }

        if (! is_array($items))
        {
            $items = array($items);
        }

        $container = $this->container;

        foreach ($items as $item)
        {
            if (is_string($item))
            {
                $item = new $item;
            }

            if (! $item instanceof IntegrationInterface)
            {
                throw new IntegrationNotFound($item);
            }

            $container = $item->define($container, $config);
        }

        $this->container = $container;

        return $this;
    }

    /**
     * Emits the headers from response and runs
     * the application.
     *
     * @return void
     */
    public function run()
    {
        if ($this->container->has(self::DEBUGGER))
        {
            $debugger = $this->container->get(self::DEBUGGER);

            if (! $debugger instanceof ErrorHandlerInterface)
            {
                throw new DebuggerNotFound($debugger);
            }

            $debugger->display();
        }

        $request = $this->container->get(self::REQUEST);

        if (! $request instanceof ServerRequestInterface)
        {
            throw new RequestNotFound($request);
        }

        echo $this->emit($request)->getBody();
    }

    /**
     * Emits the headers based from the response.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function emit(ServerRequestInterface $request)
    {
        $response = $this->handle($request);

        $code = $response->getStatusCode();

        $code .= ' ' . $response->getReasonPhrase();

        $headers = $response->getHeaders();

        $version = $response->getProtocolVersion();

        header('HTTP/' . $version . ' ' . $code);

        foreach ($headers as $name => $values)
        {
            $value = implode(',', $values);

            header($name . ': ' . $value);
        }

        return $response;
    }
}
