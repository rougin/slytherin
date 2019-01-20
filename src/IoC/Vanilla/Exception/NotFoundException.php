<?php

namespace Rougin\Slytherin\IoC\Vanilla\Exception;

use Rougin\Slytherin\Container\Exception\NotFoundException as BaseNotFoundException;

/**
 * Not Found Exception
 *
 * A specified exception in handling errors in containers.
 * NOTE: To be removed in v1.0.0. Use "Container\Exception\NotFoundException" instead.
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class NotFoundException extends BaseNotFoundException
{
}
