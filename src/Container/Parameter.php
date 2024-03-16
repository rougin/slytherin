<?php

namespace Rougin\Slytherin\Container;

/**
 * Parameter
 *
 * A backward compatible class for handling ReflectionParameter.
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
     * Returns a \ReflectionClass object for the parameter being reflected or "null".
     *
     * @return \ReflectionClass<object>|null
     * @codeCoverageIgnore
     */
    public function getClass()
    {
        $php8 = version_compare(PHP_VERSION, '8.0.0', '>=');

        if (! $php8)
        {
            return call_user_func(array($this->param, 'getClass'));
        }

        $type = call_user_func(array($this->param, 'getType'));

        $builtIn = true;

        if ($type)
        {
            $builtIn = call_user_func(array($type, 'isBuiltin'));
        }

        if ($builtIn)
        {
            return null;
        }

        /** @var callable */
        $class = array($type, 'getName');

        return new \ReflectionClass(call_user_func($class));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->getClass() ? $this->getClass()->getName() : $this->param->getName();
    }
}
