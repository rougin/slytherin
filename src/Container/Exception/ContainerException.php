<?php

namespace Rougin\Slytherin\Container\Exception;

use Psr\Container\ContainerExceptionInterface;

/**
 * Container Exception
 *
 * A specified exception in handling errors in containers.
 * NOTE: To be removed in v1.0.0. Use NotFoundException instead.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class ContainerException extends \Exception implements ContainerExceptionInterface
{
}
