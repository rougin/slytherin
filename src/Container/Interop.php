<?php

namespace Rougin\Slytherin\Container;

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
        $class = 'Psr\Container\ContainerInterface';

        $method = 'get';

        $class = new \ReflectionMethod($class, $method);

        $isV2 = method_exists($class, 'hasReturnType')
            && $class->hasReturnType();

        $container = 'Rougin\Slytherin\Container';

        $number = $isV2 ? '\V2' : '\V1';

        $orig = $container . $number . '\\' . $name;

        class_alias($orig, $container . '\Psr' . $name);
    }
}
