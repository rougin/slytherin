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
     * @var Twig_Environment
     */
    protected $twig;

    /**
     * @var array
     */
    protected $globals = [];

    /**
     * @param Twig_Environment $twig
     */
    public function __construct(Twig_Environment $twig, $globals = [])
    {
        $this->globals = $globals;
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
        $data = array_merge($data, $this->globals);

        return $this->twig->render("$template.$fileExtension", $data);
    }
}
