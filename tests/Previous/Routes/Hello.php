<?php

namespace Rougin\Slytherin\Previous\Routes;

use Rougin\Slytherin\Template\RendererInterface;

class Hello
{
    protected $renderer;

    public function __construct(RendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    public function index()
    {
        return $this->renderer->render('Greet');
    }

    public function hi($name)
    {
        return $this->renderer->render('Hello', compact('name'));
    }
}
