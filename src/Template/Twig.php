<?php

namespace Rougin\Slytherin\Template;

use Twig_Environment;
use Rougin\Slytherin\Template\RendererInterface;

/**
 * Twig
 *
 * A simple implementation of a renderer that is based on top of Sensiolab's
 * Twig template engine.
 * 
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Twig implements RendererInterface
{
    protected $renderer;

    /**
     * @param Twig_Environment $renderer
     */
    public function __construct(Twig_Environment $renderer)
    {
        $this->renderer = $renderer;
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
        return $this->renderer->render("$template.html", $data);
    }
}
