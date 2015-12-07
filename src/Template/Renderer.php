<?php

namespace Rougin\Slytherin\Template;

use Rougin\Slytherin\Template\RendererInterface;

/**
 * Renderer
 *
 * A simple implementation of a renderer that is based on top of Sensiolab's
 * Twig - a flexible, fast, and secure template engine for PHP.
 *
 * http://twig.sensiolabs.org
 * 
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Renderer implements RendererInterface
{
    protected $directories = [];

    public function __construct(array $directories)
    {
        $this->directories = $directories;
    }

    /**
     * Renders a template.
     *
     * @param  string $template
     * @param  array  $data
     * @return string
     */
    public function render($template, $data = [])
    {
        return '';
    }
}
