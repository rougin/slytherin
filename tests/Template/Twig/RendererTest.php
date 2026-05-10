<?php

namespace Rougin\Slytherin\Template\Twig;

use Rougin\Slytherin\Template\TwigLoader;
use Rougin\Slytherin\Template\TwigRenderer;
use Rougin\Slytherin\Testcase;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
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
     * @return void
     */
    public function test_passed_if_template_rendered()
    {
        $expect = 'This is a text from a template.';

        // Render the test template with PHP extension ---
        $actual = $this->renderer->render('test', array(), 'php');
        // ------------------------------------------------------

        // Verify the rendered output matches ---
        $this->assertEquals($expect, $actual);
        // --------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_template_rendered_with_data()
    {
        $expect = 'This is a text from a template.';

        // Render the Twig template with data ---
        $data = array('name' => 'template');

        $actual = $this->renderer->render('test-with-twig-data', $data);
        // -----------------------------------------

        // Verify the rendered output matches ---
        $this->assertEquals($expect, $actual);
        // --------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_template_rendered_with_globals()
    {
        $expect = 'This is a text from a template.';

        // Render the Twig template using globals ---
        $globals = array('name' => 'template');

        $renderer = new Renderer($this->twig, $globals);

        $renderer->addGlobal('test', 'wew');

        $actual = $renderer->render('test-with-twig-data');
        // ------------------------------------------------

        // Verify the rendered output matches ---
        $this->assertEquals($expect, $actual);
        // --------------------------------------
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $twig = new TwigLoader;

        // @codeCoverageIgnoreStart
        if (! $twig->exists())
        {
            $this->markTestSkipped('Twig is not installed.');
        }
        // @codeCoverageIgnoreEnd

        $path = __DIR__ . '/../../Fixture/Templates';

        $this->twig = $twig->load($path);

        $this->renderer = new TwigRenderer($this->twig);
    }
}
