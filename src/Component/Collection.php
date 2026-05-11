<?php

namespace Rougin\Slytherin\Component;

Interop::register('Collection');

/**
 * @package Slytherin
 *
 * @method \Rougin\Slytherin\Component\Collection add(string $id, $concrete)
 * @method mixed                                  get(string $id)
 * @method boolean                                has(string $id)
 * @method \Rougin\Slytherin\Component\Collection set(string $id, $concrete = null)
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Collection extends PsrCollection
{
}
