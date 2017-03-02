<?php

namespace Rougin\Slytherin\Template;

/**
 * Renderer Interface
 *
 * An interface for handling third party template engines.
 * NOTE: To be removed in v1.0.0
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
interface RendererInterface
{
    /**
     * Renders a template
     *
     * @param  string $template
     * @param  array  $data
     * @return string
     */
    public function render($template, array $data = array());
}
