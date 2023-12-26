<?php

namespace Rougin\Slytherin\Template\Vanilla;

use Rougin\Slytherin\Testcase;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class RendererTest extends Testcase
{
    /**
     * @var \Rougin\Slytherin\Template\RendererInterface
     */
    protected $renderer;

    /**
     * Sets up the renderer.
     *
     * @return void
     */
    protected function doSetUp()
    {
        $paths = array(__DIR__ . '/../../Fixture/Templates');

        $this->renderer = new Renderer($paths);
    }

    /**
     * Tests the render() method.
     *
     * @return void
     */
    public function testRenderMethod()
    {
        $result = 'This is a text from a template.';

        $actual = $this->renderer->render('test');

        $this->assertEquals($result, $actual);
    }

    /**
     * Tests the render() method with data.
     *
     * @return void
     */
    public function testRenderMethodWithData()
    {
        $expected = 'This is a text from a template.';

        $data = array('name' => 'template');

        $actual = $this->renderer->render('test-with-data', $data);

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests if the specified template is not found.
     *
     * @return void
     */
    public function testTemplateNotFound()
    {
        $this->setExpectedException('InvalidArgumentException');

        $this->renderer->render('hello');
    }
}
