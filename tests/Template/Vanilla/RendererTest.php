<?php

namespace Rougin\Slytherin\Template\Vanilla;

use Rougin\Slytherin\Template\RendererTestCases;

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
        $path = __DIR__ . '/../../Fixture/Templates';

        $this->self = new Renderer(array($path));
    }
}
