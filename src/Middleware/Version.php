<?php

namespace Rougin\Slytherin\Middleware;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Version
{
    const VERSION_0_3_0 = 'Interop\Http\Middleware\ServerMiddlewareInterface';

    const VERSION_0_4_0 = 'Interop\Http\ServerMiddleware\MiddlewareInterface';

    const VERSION_0_5_0 = 'Interop\Http\Server\MiddlewareInterface';

    const VERSION_1_0_0 = 'Psr\Http\Server\MiddlewareInterface';

    /**
     * Returns the current version installed.
     *
     * @return string|null
     * @codeCoverageIgnore
     */
    public static function get()
    {
        $hasPsr = interface_exists(self::VERSION_1_0_0);

        if (! $hasPsr && interface_exists(self::VERSION_0_3_0))
        {
            return '0.3.0';
        }

        if (! $hasPsr && interface_exists(self::VERSION_0_4_0))
        {
            return '0.4.1';
        }

        if (! $hasPsr && interface_exists(self::VERSION_0_5_0))
        {
            return '0.5.0';
        }

        return $hasPsr ? '1.0.0' : null;
    }
}
