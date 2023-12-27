<?php

namespace Rougin\Slytherin\Previous\Routes;

use Rougin\Slytherin\Template\RendererInterface;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Hello
{
    /**
     * @var \Rougin\Slytherin\Template\RendererInterface
     */
    protected $renderer;

    /**
     * @param \Rougin\Slytherin\Template\RendererInterface $renderer
     */
    public function __construct(RendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * @return string
     */
    public function index()
    {
        return $this->renderer->render('Greet');
    }

    /**
     * @param  string $name
     * @return string
     */
    public function hi($name)
    {
        return $this->renderer->render('Hello', compact('name'));
    }
}
