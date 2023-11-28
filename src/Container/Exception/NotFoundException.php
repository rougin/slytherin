<?php

namespace Rougin\Slytherin\Container\Exception;

use Rougin\Slytherin\Container\NotFoundException as BaseException;

/**
 * Not Found Exception
 *
 * A specified exception in handling errors in containers.
 * NOTE: To be removed in v1.0.0. Use Container\NotFoundException instead.
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class NotFoundException extends BaseException
{
}