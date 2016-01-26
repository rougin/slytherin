<?php

namespace Rougin\Slytherin\Test\Template;

use Rougin\Slytherin\Template\Renderer;

use PHPUnit_Framework_TestCase;

class RendererTest extends PHPUnit_Framework_TestCase
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
        $directories = [
            __DIR__ . '/../Fixtures/Templates'
        ];

        $this->renderer = new Renderer($directories);
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

        $data = [ 'name' => 'template' ];
        $rendered = $this->renderer->render('testWithData', $data);

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
