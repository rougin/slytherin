<?php

namespace Rougin\Slytherin\Fixture\Components;

use Rougin\Slytherin\Component\ComponentInterface;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class SampleComponent implements ComponentInterface
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @param string $type
     * @param mixed  $value
     */
    public function __construct($type, $value)
    {
        $this->type = $type;

        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function get()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
