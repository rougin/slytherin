<?php

namespace Rougin\Slytherin\Interfaces;

/**
 * Request Interface
 *
 * An interface for handling third party HTTP Request libraries
 * 
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
interface RequestInterface
{
    /**
     * Which request method was used to access the page:
     * i.e. 'GET', 'HEAD', 'POST', 'PUT'
     * 
     * @return string
     */
    public function getMethod();

    /**
     * Return just the path
     * 
     * @return string
     */
    public function getPath();
}
