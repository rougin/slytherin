<?php

namespace Rougin\Slytherin\Test\Fixture\Classes;

/**
 * With Interface
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class WithInterface implements NewInterface
{
    /**
     * @var string
     */
    protected $name = '';

    /**
     * @param string $name
     */
    public function __construct($name = 'Rougin')
    {
        $this->name = $name;
    }
}
