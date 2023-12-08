<?php

namespace Rougin\Slytherin\IoC;

/**
 * Auryn Container
 *
 * NOTE: To be removed in v1.0.0. Use "AurynContainer" instead.
 * 
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 * 
 * @method define($name, array $args)
 * @method defineParam($paramName, $value)
 * @method alias($original, $alias)
 * @method share($nameOrInstance)
 * @method prepare($name, $callableOrMethodStr)
 * @method delegate($name, $callableOrMethodStr)
 * @method inspect($nameFilter = null, $typeFilter = null)
 * @method make($name, array $args = array())
 * @method execute($callableOrMethodStr, array $args = array())
 * @method buildExecutable($callableOrMethodStr)
 */
class Auryn extends AurynContainer
{
}
