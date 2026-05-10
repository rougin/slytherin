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
    protected $self;

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

        $actual = $this->self->render('test', array(), 'php');

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_template_rendered_with_data()
    {
        $expect = 'This is a text from a template.';

        // Render the Twig template with data ------
        $data = array('name' => 'template');

        $file = 'test-with-twig-data';

        $actual = $this->self->render($file, $data);
        // -----------------------------------------

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_template_rendered_with_globals()
    {
        $expect = 'This is a text from a template.';

        // Render the Twig template using globals -----
        $self = new Renderer($this->twig);

        $self->addGlobal('name', 'template');

        $actual = $self->render('test-with-twig-data');
        // --------------------------------------------

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->checkIfTwigExists();

        $twig = new TwigLoader;

        $path = __DIR__ . '/../../Fixture/Templates';

        $this->twig = $twig->load($path);

        $this->self = new TwigRenderer($this->twig);
    }
}
