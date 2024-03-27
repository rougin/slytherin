<?php

namespace Rougin\Slytherin\Fixture\Classes;

/**
 * Another Class
 *
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class AnotherClass
{
    /**
     * @var string
     */
    protected $text;

    /**
     * @param string $text
     */
    public function __construct($text = 'Hello')
    {
        $this->text = $text;
    }
}
