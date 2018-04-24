<?php

namespace Rougin\Slytherin;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
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
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Application
{
    // NOTE: To be removed in v1.0.0
    const ERROR_HANDLER = 'Rougin\Slytherin\Debug\ErrorHandlerInterface';

    const MIDDLEWARE = 'Rougin\Slytherin\Middleware\MiddlewareInterface';

    const SERVER_REQUEST = 'Psr\Http\Message\ServerRequestInterface';

    /**
     * @var \Rougin\Slytherin\Integration\ConfigurationInterface
     */
    protected $config;

    /**
     * @var \Psr\Container\ContainerInterface
     */
    protected static $container;

    /**
     * Initializes the application instance.
     *
     * @param \Psr\Container\ContainerInterface|null                    $container
     * @param \Rougin\Slytherin\Integration\ConfigurationInterface|null $config
     */
    public function __construct(ContainerInterface $container = null, ConfigurationInterface $config = null)
    {
        $this->config = $config === null ? new Configuration : $config;

        static::$container = $container === null ? new Container : $container;
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
        $callback = new CallbackHandler(self::$container);

        if (static::$container->has(self::MIDDLEWARE)) {
            $middleware = static::$container->get(self::MIDDLEWARE);

            $delegate = new Delegate($callback);

            $result = $middleware->process($request, $delegate);
        }

        return isset($result) ? $result : $callback($request);
    }

    /**
     * Adds the specified integrations to the container.
     *
     * @param  \Rougin\Slytherin\Integration\IntegrationInterface|array|string $integrations
     * @param  \Rougin\Slytherin\Integration\ConfigurationInterface|null       $config
     * @return self
     */
    public function integrate($integrations, ConfigurationInterface $config = null)
    {
        list($config, $container) = array($config ?: $this->config, static::$container);

        foreach ((array) $integrations as $item) {
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
        if (static::$container->has(self::ERROR_HANDLER)) {
            $debugger = static::$container->get(self::ERROR_HANDLER);

            $debugger->display();
        }

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

        foreach ($headers as $name => $values) {
            $value = (string) implode(',', $values);

            header((string) $name . ': ' . $value);
        }

        return $response;
    }
}
