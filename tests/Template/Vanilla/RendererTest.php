<?php

namespace Rougin\Slytherin\Template\Vanilla;

class RendererTest extends \PHPUnit_Framework_TestCase
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
    public function setUp()
    {
        $directories = array(__DIR__ . '/../../Fixture/Templates');

        $this->renderer = new \Rougin\Slytherin\Template\Vanilla\Renderer($directories);
    }

    /**
     * Tests the render() method.
     *
     * @return void
     */
    public function testRenderMethod()
    {
        $result = 'This is a text from a template.';

        $this->assertEquals($result, $this->renderer->render('test'));
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

        $rendered = $this->renderer->render('test-with-data', $data);

        $this->assertEquals($expected, $rendered);
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
