<?php

namespace Rougin\Slytherin\Container;

use Psr\Container\ContainerExceptionInterface;

/**
 * Container Exception
 *
 * A specified exception in handling errors in containers.
 *
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class ContainerException extends \Exception implements ContainerExceptionInterface
{
}
