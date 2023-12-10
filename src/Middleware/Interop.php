<?php

namespace Rougin\Slytherin\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Rougin\Slytherin\Middleware\Handlers\Handler030;
use Rougin\Slytherin\Middleware\Handlers\Handler041;
use Rougin\Slytherin\Middleware\Handlers\Handler050;
use Rougin\Slytherin\Middleware\Handlers\Handler100;

/**
 * Interop Handler
 *
 * A handler that allows all "http-interop" middlewares to interact with Slytherin.
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 * @codeCoverageIgnore
 */
class Interop implements HandlerInterface
{
    const VERSION_0_3_0 = 'Interop\Http\Middleware\DelegateInterface';

    const VERSION_0_4_1 = 'Interop\Http\ServerMiddleware\DelegateInterface';

    const VERSION_0_5_0 = 'Interop\Http\Server\RequestHandlerInterface';

    const VERSION_1_0_0 = 'Psr\Http\Server\RequestHandlerInterface';

    /**
     * @var mixed
     */
    protected $handler;

    /**
     * @param mixed $handler
     */
    public function __construct($handler)
    {
        $this->handler = $handler;
    }

    /**
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request)
    {
        return $this->handle($request);
    }

    /**
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request)
    {
        $version = Version::get();

        $method = 'handle';

        if ($version === '0.3.0' || $version === '0.4.1')
        {
            $method = 'process';
        }

        $class = array($this->handler, $method);

        return call_user_func($class, $request);
    }

    /**
     * @return boolean
     */
    public static function exists()
    {
        return interface_exists(self::VERSION_0_3_0)
            || interface_exists(self::VERSION_0_4_1)
            || interface_exists(self::VERSION_0_5_0);
    }

    /**
     * @param  mixed       $handler
     * @param  string|null $version
     * @return mixed
     */
    public static function getHandler($handler, $version = null)
    {
        switch ($version)
        {
            case '0.3.0':
                return new Handler030($handler);

            case '0.4.1':
                return new Handler041($handler);

            case '0.5.0':
                return new Handler050($handler);

            case '1.0.0':
                return new Handler100($handler);
        }

        if (self::hasVersion($handler, self::VERSION_0_3_0))
        {
            return new Handler030($handler);
        }

        if (self::hasVersion($handler, self::VERSION_0_4_1))
        {
            return new Handler041($handler);
        }

        if (self::hasVersion($handler, self::VERSION_0_5_0))
        {
            return new Handler050($handler);
        }

        if (self::hasVersion($handler, self::VERSION_1_0_0))
        {
            return new Handler100($handler);
        }

        return $handler;
    }

    /**
     * @param  mixed  $handler
     * @param  string $version
     * @return boolean
     */
    public static function hasVersion($handler, $class)
    {
        return interface_exists($class) || is_a($handler, $class);
    }

    /**
     * @return boolean
     */
    public static function psrExists()
    {
        return interface_exists(self::VERSION_1_0_0);
    }
}
