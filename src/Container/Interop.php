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
     * @return boolean
     */
    public static function isVersion2()
    {
        $class = 'Psr\Container\ContainerInterface';

        $method = 'has';

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
        $num = self::isVersion2() ? '\V2' : '\V1';

        $container = 'Rougin\Slytherin\Container';

        $orig = $container . $num . '\\' . $name;

        class_alias($orig, $container . '\Psr' . $name);
    }
}
