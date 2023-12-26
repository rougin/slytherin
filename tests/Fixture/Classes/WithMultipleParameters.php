<?php

namespace Rougin\Slytherin\Fixture\Classes;

/**
 * With Multiple Parameters
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class WithMultipleParameters
{
    /**
     * @var array<string, string>
     */
    protected $data = array();

    /**
     * @var array<string, string>
     */
    protected $fields = array();

    /**
     * @var string|null
     */
    protected $lang = null;

    /**
     * @var string|null
     */
    protected $dir = null;

    /**
     * @param array<string, string> $data
     * @param array<string, string> $fields
     * @param string|null           $lang
     * @param string|null           $dir
     */
    public function __construct($data = array(), $fields = array(), $lang = null, $dir = null)
    {
        $this->data = $data;

        $this->fields = $fields;

        $this->lang = $lang;

        $this->dir = $dir;
    }

    /**
     * Returns with a string of "With multiple parameters".
     *
     * @return string
     */
    public function index()
    {
        return 'With multiple parameters';
    }
}
