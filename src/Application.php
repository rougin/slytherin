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

    /**
     * @var \Rougin\Slytherin\Integration\Configuration
     */
    protected $config;

    /**
     * @var \Psr\Container\ContainerInterface
     */
    protected static $container;

    /**
     * @param \Psr\Container\ContainerInterface|null $container
     */
    public function __construct(ContainerInterface $container = null, Integration\Configuration $config = null)
    {
        $this->config = $config ?: new Integration\Configuration;

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
        $exists = interface_exists('Interop\Http\ServerMiddleware\MiddlewareInterface');

        $callback = new Application\CallbackHandler(self::$container);

        if ($exists && static::$container->has(self::MIDDLEWARE_DISPATCHER)) {
            $middleware = static::$container->get(self::MIDDLEWARE_DISPATCHER);

            $delegate = new Middleware\Delegate($callback);

            $result = $middleware->process($request, $delegate);
        }

        return (isset($result)) ? $result : $callback($request);
    }

    /**
     * Adds the specified integrations to the container.
     *
     * @param  \Rougin\Slytherin\Integration\IntegrationInterface|array|string $integrations
     * @param  \Rougin\Slytherin\Integration\Configuration                     $config
     * @return self
     */
    public function integrate($integrations, Integration\Configuration $config = null)
    {
        list($config, $container) = array($config ?: $this->config, static::$container);

        $integrations = is_array($integrations) ? $integrations : array($integrations);

        foreach ($integrations as $integration) {
            $integration = is_string($integration) ? new $integration : $integration;

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
}
