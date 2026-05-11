<?php

namespace Rougin\Slytherin\Container;

Interop::register('ReflectionContainer');

/**
 * @package Slytherin
 *
 * @method mixed                 get(string $id)
 * @method array<integer, mixed> getArguments(\ReflectionFunctionAbstract $reflector, array<string, mixed> $parameters = array())
 * @method boolean               has(string $id)
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class ReflectionContainer extends PsrReflectionContainer
{
}
