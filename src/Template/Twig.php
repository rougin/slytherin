<?php

namespace Rougin\Slytherin\Template;

use League\Url\Url;
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
    protected $url;

    /**
     * @param Twig_Environment $renderer
     */
    public function __construct(Twig_Environment $renderer)
    {
        $this->renderer = $renderer;
        $this->url = Url::createFromServer($_SERVER);
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
        $data['baseUrl'] = $this->url->getBaseUrl();

        return $this->renderer->render("$template.html", $data);
    }
}
