<?php

namespace Rougin\Slytherin\Template;

/**
 * Renderer Test
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class RendererTest extends \Rougin\Slytherin\Testcase
{
    /**
     * @var \Rougin\Slytherin\Template\RendererInterface
     */
    protected $renderer;

    /**
     * Sets up the renderer instance.
     *
     * @return void
     */
    protected function doSetUp()
    {
        $root = str_replace('Template', 'Fixture', __DIR__);

        $directories = (string) $root . '/Templates';

        $this->renderer = new Renderer($directories);
    }

    /**
     * Tests RendererInterface::render.
     *
     * @return void
     */
    public function testRenderMethod()
    {
        $expected = 'This is a text from a template.';

        $actual = $this->renderer->render('test');

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests RendererInterface::render with data.
     *
     * @return void
     */
    public function testRenderMethodWithData()
    {
        $expected = (string) 'This is a text from a template.';

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
