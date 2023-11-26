<?php

namespace Rougin\Slytherin;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rougin\Slytherin\Application\CallbackHandler;
use Rougin\Slytherin\Container\Container;
use Rougin\Slytherin\Integration\ConfigurationInterface;
use Rougin\Slytherin\Middleware\Delegate;

/**
 * Application
 *
 * Integrates all specified components into the application.
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Application
{
    // NOTE: To be removed in v1.0.0 ------------------------------------
    const ERROR_HANDLER = 'Rougin\Slytherin\Debug\ErrorHandlerInterface';
    // ------------------------------------------------------------------

    const MIDDLEWARE = 'Interop\Http\ServerMiddleware\MiddlewareInterface';

    const RENDERER = 'Rougin\Slytherin\Template\RendererInterface';

    const ROUTER = 'Rougin\Slytherin\Routing\RouterInterface';

    const SERVER_REQUEST = 'Psr\Http\Message\ServerRequestInterface';

    /**
     * @var \Rougin\Slytherin\Integration\Configuration
     */
    protected $config;

    /**
     * @var \Rougin\Slytherin\Container\ContainerInterface
     */
    protected static $container;

    /**
     * Initializes the application instance.
     *
     * @param \Rougin\Slytherin\Container\ContainerInterface|null $container
     * @param \Rougin\Slytherin\Integration\Configuration|null    $config
     */
    public function __construct(ContainerInterface $container = null, ConfigurationInterface $config = null)
    {
        if (! $config) $config = new Configuration;

        $this->config = $config;

        if (! $container) $container = new Container;

        static::$container = $container;
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
        $callback = new CallbackHandler(self::$container);

        $hasMiddleware = static::$container->has(self::MIDDLEWARE);

        if (! $hasMiddleware) return $callback($request);

        /** @var \Interop\Http\ServerMiddleware\MiddlewareInterface */
        $middleware = static::$container->get(self::MIDDLEWARE);

        $delegate = new Delegate($callback);

        return $middleware->process($request, $delegate);
    }

    /**
     * Adds the specified integrations to the container.
     *
     * @param  \Rougin\Slytherin\Integration\IntegrationInterface[]|string[]|string $integrations
     * @param  \Rougin\Slytherin\Integration\Configuration|null                     $config
     * @return self
     */
    public function integrate($integrations, ConfigurationInterface $config = null)
    {
        if (! $config) $config = $this->config;

        $container = static::$container;

        foreach ((array) $integrations as $item)
        {
            /** @var \Rougin\Slytherin\Integration\IntegrationInterface */
            $integration = is_string($item) ? new $item : $item;

            $container = $integration->define($container, $config);
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
        // NOTE: To be removed in v1.0.0. Use "ErrorHandlerIntegration" instead.
        if (static::$container->has(self::ERROR_HANDLER))
        {
            /** @var \Rougin\Slytherin\Debug\ErrorHandlerInterface */
            $debugger = static::$container->get(self::ERROR_HANDLER);

            $debugger->display();
        }

        /** @var \Psr\Http\Message\ServerRequestInterface */
        $request = static::$container->get(self::SERVER_REQUEST);

        echo (string) $this->emit($request)->getBody();
    }

    /**
     * Emits the headers based from the response.
     * NOTE: To be removed in v1.0.0. Should be included in run().
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