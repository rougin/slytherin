<?php

namespace Rougin\Slytherin\Template;

/**
 * Twig Renderer
 *
 * A simple implementation of a template renderer that is based on top of
 * Sensiolab's Twig - a flexible, fast, and secure template engine for PHP.
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 *
 * @link http://twig.sensiolabs.org
 */
class TwigRenderer implements RendererInterface
{
    /**
     * @var \Twig\Environment
     */
    protected $twig;

    /**
     * @param \Twig\Environment     $twig
     * @param array<string, string> $globals (NOTE: To be removed in v1.0.0).
     */
    public function __construct($twig, array $globals = array())
    {
        $this->twig = $twig;

        foreach ($globals as $key => $value)
        {
            $this->addGlobal($key, $value);
        }
    }

    /**
     * @param  string $name
     * @param  mixed  $value
     * @return self
     */
    public function addGlobal($name, $value)
    {
        $this->twig->addGlobal($name, $value);

        return $this;
    }

    /**
     * Renders a template.
     *
     * @param  string               $template
     * @param  array<string, mixed> $data
     * @param  string               $extension
     * @return string
     */
    public function render($template, array $data = array(), $extension = 'html')
    {
        return $this->twig->render("$template.$extension", $data);
    }
}
