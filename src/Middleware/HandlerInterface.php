<?php

namespace Rougin\Slytherin;

$handlers = array();

$handlers['0.3.0'] = 'Interop\Http\Middleware\DelegateInterface';

$handlers['0.4.1'] = 'Interop\Http\ServerMiddleware\DelegateInterface';

$handlers['0.5.0'] = 'Interop\Http\Server\RequestHandlerInterface';

$handlers['1.0.0'] = 'Psr\Http\Server\RequestHandlerInterface';

$method = (string) 'process';

$handler = (string) 'Rougin\Slytherin\Middleware\HandlerInterface';

foreach ((array) array_keys($handlers) as $version)
{
    $defined = interface_exists((string) $handler);

    if (! $defined && interface_exists($handlers[$version]))
    {
        class_alias($handlers[$version], $handler);

        if (method_exists($handler, 'handle'))
        {
            $method = 'handle';
        }

        if (method_exists($handler, 'next'))
        {
            $method = 'next';
        }

        define('HANDLER_METHOD', (string) $method);
    }
}
