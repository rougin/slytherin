<?php declare(strict_types = 1);

namespace Rougin\Slytherin\Container;

use Psr\Container\NotFoundExceptionInterface;

/**
 * Not Found Exception
 *
 * A specified exception in handling errors in containers.
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class NotFoundException extends \InvalidArgumentException implements NotFoundExceptionInterface
{
}
