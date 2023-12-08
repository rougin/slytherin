<?php

namespace Rougin\Slytherin\Template\Twig;

use Twig_Environment;

use Rougin\Slytherin\Template\RendererInterface;

/**
 * Renderer
 *
 * A simple implementation of a template renderer that is based on top of
 * Sensiolab's Twig - a flexible, fast, and secure template engine for PHP.
 *
 * http://twig.sensiolabs.org
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Renderer implements RendererInterface
{
    /**
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * @param Twig_Environment $twig
     * @param array            $globals
     */
    public function __construct(Twig_Environment $twig, array $globals = [])
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
    public function render($template, array $data = [], $fileExtension = 'html')
    {
        return $this->twig->render("$template.$fileExtension", $data);
    }
}
