<?php

namespace Rougin\Slytherin\System\Errors;

use Rougin\Slytherin\Container\NotFoundException;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
abstract class NotFound extends NotFoundException
{
    /**
     * @var string
     */
    protected static $expected = '';

    /**
     * @param mixed $item
     */
    public function __construct($item)
    {
        $message = self::format($item, static::$expected);

        parent::__construct($message);
    }

    /**
     * @param mixed  $item
     * @param string $expected
     *
     * @return string
     */
    protected static function format($item, $expected)
    {
        $item = is_object($item) ? get_class($item) : $item;

        $item = is_string($item) ? $item : gettype($item);

        return '"' . $item . '" is not under "' . $expected . '"';
    }
}
