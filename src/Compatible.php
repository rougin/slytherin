<?php

namespace Rougin\Slytherin;

// @codeCoverageIgnoreStart
list($handlers, $middlewares) = array(array(), array());

$middlewares['0.3.0'] = 'Interop\Http\Middleware\ServerMiddlewareInterface';
$middlewares['0.4.1'] = 'Interop\Http\ServerMiddleware\MiddlewareInterface';
$middlewares['0.5.0'] = 'Interop\Http\Server\MiddlewareInterface';
$middlewares['1.0.0'] = 'Psr\Http\Server\MiddlewareInterface';

$handlers['0.3.0'] = 'Interop\Http\Middleware\DelegateInterface';
$handlers['0.4.1'] = 'Interop\Http\ServerMiddleware\DelegateInterface';
$handlers['0.5.0'] = 'Interop\Http\Server\RequestHandlerInterface';
$handlers['1.0.0'] = 'Psr\Http\Server\RequestHandlerInterface';

foreach ((array) array_keys($middlewares) as $version) {
    $exists = interface_exists($middlewares[$version]);

    $middleware = 'Rougin\Slytherin\Middleware\MiddlewareInterface';

    $defined = interface_exists((string) $middleware);

    $handler = 'Rougin\Slytherin\Middleware\HandlerInterface';

    if (! $defined && $exists && interface_exists($handlers[$version])) {
        class_alias($middlewares[$version], $middleware);

        class_alias($handlers[$version], (string) $handler);

        $method = (string) 'process';

        method_exists($handler, 'next') && $method = 'next';

        method_exists($handler, 'handle') && $method = 'handle';

        define('HANDLER_METHOD', (string) $method);
    }
}
// @codeCoverageIgnoreEnd