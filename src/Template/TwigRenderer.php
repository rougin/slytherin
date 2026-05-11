<?php

namespace Rougin\Slytherin\Template;

/**
 * A template renderer built on top of
 * Sensiolabs' Twig template engine.
 *
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 *
 * @link https://twig.sensiolabs.org
 */
class TwigRenderer implements RendererInterface
{
    /**
     * @var \Twig\Environment|\Twig_Environment
     */
    protected $twig;

    /**
     * @param \Twig\Environment|\Twig_Environment $twig
     */
    public function __construct($twig)
    {
        $this->twig = $twig;
    }

    /**
     * @param string $name
     * @param mixed  $value
     *
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
     * @param string               $template
     * @param array<string, mixed> $data
     * @param string               $extension
     *
     * @return string
     */
    public function render($template, array $data = array(), $extension = 'html')
    {
        return $this->twig->render("$template.$extension", $data);
    }
}
