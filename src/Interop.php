<?php

namespace Rougin\Slytherin;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
abstract class Interop
{
    /**
     * @return string
     */
    protected static function psrClass()
    {
        return '';
    }

    /**
     * @return string
     */
    protected static function psrMethod()
    {
        return '';
    }

    /**
     * @return boolean
     */
    public static function isVersion2()
    {
        $class = static::psrClass();

        $method = static::psrMethod();

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
        $class = get_called_class();

        $pos = strrpos($class, '\\');

        $ns = $pos !== false ? substr($class, 0, $pos) : '';

        $num = static::isVersion2() ? '\V2' : '\V1';

        $orig = $ns . $num . '\\' . $name;

        class_alias($orig, $ns . '\Psr' . $name);
    }
}
