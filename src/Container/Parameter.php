<?php

namespace Rougin\Slytherin\Container;

/**
 * Parameter
 *
 * A backward compatible class for handling ReflectionParameter.
 * NOTE: This will only work on PHP <= 8.0. Needs to be refactored.
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Parameter
{
    /**
     * @var \ReflectionParameter
     */
    protected $param;

    /**
     * Initializes the parameter instance.
     *
     * @param \ReflectionParameter $param
     */
    public function __construct(\ReflectionParameter $param)
    {
        $this->param = $param;
    }

    /**
     * Gets a \ReflectionClass object for the parameter being reflected or "null".
     *
     * @return \ReflectionClass<object>|null
     */
    public function getClass()
    {
        $php8 = version_compare(PHP_VERSION, '8.0.0', '>=');

        if (! $php8)
        {
            $method = array($this->param, 'getClass');

            return call_user_func((array) $method);
        }

        $method = array($this->param, 'getType');

        $type = call_user_func((array) $method);

        $builtIn = true;

        if ($type)
        {
            $method = array($type, 'isBuiltin');

            $builtIn = call_user_func((array) $method);
        }

        if ($builtIn) return null;

        /** @var callable */
        $class = array($type, 'getName');

        $class = call_user_func($class);

        return new \ReflectionClass($class);
    }
}