<?php

namespace Rougin\Slytherin\IoC\Vanilla\Exception;

use InvalidArgumentException;
use Interop\Container\Exception\NotFoundException as InteropNotFoundException;

/**
 * Not Found Exception
 *
 * A specified exception in handling errors in containers.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class NotFoundException extends InvalidArgumentException implements InteropNotFoundException
{
}
