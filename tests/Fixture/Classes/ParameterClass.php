<?php

namespace Rougin\Slytherin\Fixture\Classes;

/**
 * Parameter Class
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class ParameterClass
{
    /**
     * @var \Rougin\Slytherin\Fixture\Classes\WithMultipleParameters
     */
    protected $class;

    /**
     * @param \Rougin\Slytherin\Fixture\Classes\WithMultipleParameters $class
     */
    public function __construct(WithMultipleParameters $class)
    {
        $this->class = $class;
    }

    /**
     * Returns with a string of "With multiple parameters".
     *
     * @return string
     */
    public function index()
    {
        return $this->class->index();
    }
}
