<?php

namespace Rougin\Slytherin\Fixture\Classes;

/**
 * Another Class
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
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

    /**
     * Returns a string 'Hello'.
     *
     * @return string
     */
    public function index()
    {
        return $this->text;
    }

    /**
     * Returns a string 'Store'.
     *
     * @return string
     */
    public function store()
    {
        return 'Store';
    }
}
