<?php

namespace Rougin\Slytherin\Test\Template\Twig;

use Twig_Environment;
use Twig_Loader_Filesystem;
use Rougin\Slytherin\Template\TwigRenderer;

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
        if (! class_exists('Twig_Environment')) {
            $this->markTestSkipped('Twig is not installed.');
        }

        $loader = new Twig_Loader_Filesystem(__DIR__ . '/../../Fixture/Templates');
        $twig = new Twig_Environment($loader);

        $this->renderer = new TwigRenderer($twig);
    }

    /**
     * Tests the render() method.
     *
     * @return void
     */
    public function testRenderMethod()
    {
        $result = 'This is a text from a template.';

        $this->assertEquals($result, $this->renderer->render('test', [], 'php'));
    }

    /**
     * Tests the render() method with data.
     *
     * @return void
     */
    public function testRenderMethodWithData()
    {
        $expected = 'This is a text from a template.';

        $data     = [ 'name' => 'template' ];
        $rendered = $this->renderer->render('test-with-twig-data', $data, 'php');

        $this->assertEquals($expected, $rendered);
    }
}
