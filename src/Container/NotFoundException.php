<?php

namespace Rougin\Slytherin\Container;

use Psr\Container\NotFoundExceptionInterface;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class NotFoundException extends \InvalidArgumentException implements NotFoundExceptionInterface
{
}
