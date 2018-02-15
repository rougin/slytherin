<?php

namespace Rougin\Slytherin\Template;

/**
 * Renderer Test
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class RendererTest extends \PHPUnit_Framework_TestCase
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
    public function setUp()
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

        $result = $this->renderer->render('test');

        $this->assertEquals($expected, $result);
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

        $result = $this->renderer->render('test-with-data', $data);

        $this->assertEquals($expected, $result);
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
