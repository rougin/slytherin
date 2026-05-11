<?php

namespace Rougin\Slytherin\Http;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Interop
{
    /**
     * @param string $name
     *
     * @return void
     */
    public static function register($name)
    {
        $class = 'Psr\Http\Message\MessageInterface';

        $method = 'getProtocolVersion';

        $class = new \ReflectionMethod($class, $method);

        $isV2 = method_exists($class, 'hasReturnType')
            && $class->hasReturnType();

        $http = 'Rougin\Slytherin\Http';

        $number = $isV2 ? '\V2' : '\V1';

        $orig = $http . $number . '\\' . $name;

        class_alias($orig, $http . '\Psr' . $name);
    }
}
