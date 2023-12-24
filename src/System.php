<?php

namespace Rougin\Slytherin;

use Psr\Http\Message\ServerRequestInterface;
use Rougin\Slytherin\Container\Container;
use Rougin\Slytherin\Container\ContainerInterface;
use Rougin\Slytherin\Integration\Configuration;
use Rougin\Slytherin\System\Handler;

/**
 * System Application
 *
 * Integrates all specified components into the application.
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class System
{
    const CONFIG = 'Rougin\Slytherin\Integration\Configuration';

    const CONTAINER = 'Rougin\Slytherin\Container\ContainerInterface';

    const DEBUGGER = 'Rougin\Slytherin\Debug\ErrorHandlerInterface';

    const DISPATCHER = 'Rougin\Slytherin\Routing\DispatcherInterface';

    const MIDDLEWARE = 'Rougin\Slytherin\Middleware\DispatcherInterface';

    const REQUEST = 'Psr\Http\Message\ServerRequestInterface';

    const RESPONSE = 'Psr\Http\Message\ResponseInterface';

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
     */
    public function __construct(ContainerInterface $container = null, Configuration $config = null)
    {
        if (! $config) $config = new Configuration;

        $this->config = $config;

        if (! $container) $container = new Container;

        $this->container = $container;
    }

    /**
     * Handles the ServerRequestInterface to convert it to a ResponseInterface.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request)
    {
        $uri = $request->getUri()->getPath();

        $method = $request->getMethod();

        /** @var \Rougin\Slytherin\Routing\DispatcherInterface */
        $dispatcher = $this->container->get(self::DISPATCHER);

        if ($this->container->has(self::ROUTER))
        {
            /** @var \Rougin\Slytherin\Routing\RouterInterface */
            $router = $this->container->get(self::ROUTER);

            $dispatcher = $dispatcher->setRouter($router);
        }

        $route = $dispatcher->dispatch($method, $uri);

        $items = $route->getMiddlewares();

        $handler = new Handler($route, $this->container);

        if (! $this->container->has(self::MIDDLEWARE))
        {
            return $handler->handle($request);
        }

        /** @var \Rougin\Slytherin\Middleware\DispatcherInterface */
        $middleware = $this->container->get(self::MIDDLEWARE);

        $stack = $middleware->getStack();

        $middleware->setStack(array_merge($items, $stack));

        return $middleware->process($request, $handler);
    }

    /**
     * Adds the specified integrations to the container.
     *
     * @param  mixed[]|string                                   $items
     * @param  \Rougin\Slytherin\Integration\Configuration|null $config
     * @return self
     */
    public function integrate($items, Configuration $config = null)
    {
        if (! $config) $config = $this->config;

        if (is_string($items)) $items = array($items);

        $container = $this->container;

        /** @var \Rougin\Slytherin\Integration\IntegrationInterface|string $item */
        foreach ($items as $item)
        {
            if (is_string($item))
            {
                /** @var \Rougin\Slytherin\Integration\IntegrationInterface */
                $item = new $item;
            }

            $container = $item->define($container, $config);
        }

        $this->container = $container;

        return $this;
    }

    /**
     * Emits the headers from response and runs the application.
     *
     * @return void
     */
    public function run()
    {
        if ($this->container->has(self::DEBUGGER))
        {
            /** @var \Rougin\Slytherin\Debug\ErrorHandlerInterface */
            $debugger = $this->container->get(self::DEBUGGER);

            $debugger->display();
        }

        /** @var \Psr\Http\Message\ServerRequestInterface */
        $request = $this->container->get(self::REQUEST);

        echo (string) $this->emit($request)->getBody();
    }

    /**
     * Emits the headers based from the response.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function emit(ServerRequestInterface $request)
    {
        $response = $this->handle($request);

        $code = (string) $response->getStatusCode();

        $code .= ' ' . $response->getReasonPhrase();

        $headers = (array) $response->getHeaders();

        $version = $response->getProtocolVersion();

        header('HTTP/' . $version . ' ' . $code);

        foreach ($headers as $name => $values)
        {
            $value = (string) implode(',', $values);

            header((string) $name . ': ' . $value);
        }

        return $response;
    }
}
