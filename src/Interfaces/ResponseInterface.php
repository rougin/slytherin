<?php

namespace Rougin\Slytherin\Interfaces;

/**
 * Response Interface
 *
 * An interface for handling third party HTTP Response libraries
 * 
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
interface ResponseInterface
{
    /**
     * Sets the body content
     * 
     * @param  string $content
     * @return void
     */
    public function setContent($content);

    /**
     * Sets the HTTP status code
     * 
     * @param  integer $code
     * @param  string  $text
     * @return void
     */
    public function setStatusCode($code, $text = null);

    /**
     * Returns the body content
     * 
     * @return string
     */
    public function getContent();
}
