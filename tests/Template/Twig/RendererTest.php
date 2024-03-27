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
    protected function doSetUp()
    {
        $twig = new TwigLoader;

        // @codeCoverageIgnoreStart
        if (! $twig->exists())
        {
            $this->markTestSkipped('Twig is not installed.');
        }
        // @codeCoverageIgnoreEnd

        $path = realpath(__DIR__ . '/../../Fixture/Templates');

        $this->twig = $twig->load((string) $path);

        $this->renderer = new TwigRenderer($this->twig);
    }

    /**
     * @return void
     */
    public function test_rendering_a_text_from_file()
    {
        $expected = 'This is a text from a template.';

        $actual = $this->renderer->render('test', array(), 'php');

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_rendering_a_text_from_file_with_data()
    {
        $expected = 'This is a text from a template.';

        $data = array('name' => 'template');

        $actual = $this->renderer->render('test-with-twig-data', $data);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_rendering_a_text_from_file_with_globals()
    {
        $expected = 'This is a text from a template.';

        $globals  = array('name' => 'template');

        $renderer = new Renderer($this->twig, $globals);

        $renderer->addGlobal('test', 'wew');

        $actual = $renderer->render('test-with-twig-data');

        $this->assertEquals($expected, $actual);
    }
}
