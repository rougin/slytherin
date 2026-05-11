<?php

namespace Rougin\Slytherin\Container;

Interop::register('Container');

/**
 * @package Slytherin
 *
 * @method \Rougin\Slytherin\Container\ContainerInterface add(string $id, $concrete)
 * @method \Rougin\Slytherin\Container\ContainerInterface alias(string $id, string $original)
 * @method mixed                                          get(string $id)
 * @method boolean                                        has(string $id)
 * @method \Rougin\Slytherin\Container\ContainerInterface set(string $id, $concrete)
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Container extends PsrContainer
{
}
