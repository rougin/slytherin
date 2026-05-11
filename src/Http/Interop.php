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
     * @return boolean
     */
    public static function isVersion2()
    {
        $class = 'Psr\Http\Message\MessageInterface';

        $method = 'getProtocolVersion';

        $class = new \ReflectionMethod($class, $method);

        return method_exists($class, 'hasReturnType')
            && $class->hasReturnType();
    }

    /**
     * @param string $name
     *
     * @return void
     */
    public static function register($name)
    {
        $http = 'Rougin\Slytherin\Http';

        $isV2 = self::isVersion2() ? '\V2' : '\V1';

        $orig = $http . $isV2 . '\\' . $name;

        class_alias($orig, $http . '\Psr' . $name);
    }
}
