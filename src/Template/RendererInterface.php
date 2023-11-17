<?php

namespace Rougin\Slytherin\Template;

/**
 * Renderer Interface
 *
 * An interface for handling third party template engines.
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
interface RendererInterface
{
    /**
     * Renders a template.
     *
     * @param  string               $template
     * @param  array<string, mixed> $data
     * @return string
     */
    public function render($template, array $data = array());
}