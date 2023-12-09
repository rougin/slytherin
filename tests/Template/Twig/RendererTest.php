<?php

namespace Rougin\Slytherin\Template\Twig;

use Rougin\Slytherin\Template\TwigLoader;
use Rougin\Slytherin\Template\TwigRenderer;
use Rougin\Slytherin\Testcase;

class RendererTest extends Testcase
{
    /**
     * @var \Rougin\Slytherin\Template\TwigRenderer
     */
    protected $renderer;

    /**
     * @var \Twig\Environment
     */
    protected $twig;

    /**
     * Sets up the renderer.
     *
     * @return void
     */
    protected function doSetUp()
    {
        $twig = new TwigLoader;

        if (! $twig->exists())
        {
            $this->markTestSkipped('Twig is not installed.');
        }

        $path = realpath(__DIR__ . '/../../Fixture/Templates');

        $this->twig = $twig->load((string) $path);

        $this->renderer = new TwigRenderer($this->twig);
    }

    /**
     * Tests the render() method.
     *
     * @return void
     */
    public function testRenderMethod()
    {
        $expected = 'This is a text from a template.';

        $rendered = $this->renderer->render('test', array(), 'php');

        $this->assertEquals($expected, $rendered);
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

        $rendered = $this->renderer->render('test-with-twig-data', $data, 'php');

        $this->assertEquals($expected, $rendered);
    }

    /**
     * Tests the render() method with a global variable.
     *
     * @return void
     */
    public function testRenderMethodWithGlobals()
    {
        $expected = 'This is a text from a template.';

        $globals  = array('name' => 'template');

        $renderer = new Renderer($this->twig, $globals);

        $renderer->addGlobal('test', 'wew');

        $rendered = $renderer->render('test-with-twig-data', array(), 'php');

        $this->assertEquals($expected, $rendered);
    }
}
