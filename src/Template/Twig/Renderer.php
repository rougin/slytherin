<?php

namespace Rougin\Slytherin\Template\Twig;

/**
 * Renderer
 *
 * A simple implementation of a template renderer that is based on top of
 * Sensiolab's Twig - a flexible, fast, and secure template engine for PHP.
 * NOTE: To be removed in v1.0.0
 *
 * http://twig.sensiolabs.org
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Renderer implements \Rougin\Slytherin\Template\RendererInterface
{
    /**
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * @param Twig_Environment $twig
     * @param array            $globals
     */
    public function __construct(\Twig_Environment $twig, array $globals = array())
    {
        foreach ($globals as $key => $value) {
            $twig->addGlobal($key, $value);
        }

        $this->twig = $twig;
    }

    /**
     * Renders a template.
     *
     * @param  string $template
     * @param  array  $data
     * @param  string $fileExtension
     * @return string
     */
    public function render($template, array $data = array(), $fileExtension = 'twig')
    {
        return $this->twig->render("$template.$fileExtension", $data);
    }
}
