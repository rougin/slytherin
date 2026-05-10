<?php

namespace Rougin\Slytherin\Template;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class RendererTest extends RendererTestCases
{
    /**
     * @return void
     */
    protected function doSetUp()
    {
        $root = str_replace('Template', 'Fixture', __DIR__);

        $this->self = new Renderer($root . '/Templates');
    }
}
