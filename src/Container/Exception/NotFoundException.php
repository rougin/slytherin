<?php

namespace Rougin\Slytherin\Container\Exception;

use Psr\Container\NotFoundExceptionInterface;

/**
 * Not Found Exception
 *
 * A specified exception in handling errors in containers.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class NotFoundException extends \InvalidArgumentException implements NotFoundExceptionInterface
{
}
