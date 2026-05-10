<?php

namespace Rougin\Slytherin\Template\Vanilla;

use Rougin\Slytherin\Testcase;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class RendererTest extends Testcase
{
    /**
     * @var \Rougin\Slytherin\Template\RendererInterface
     */
    protected $renderer;

    /**
     * @return void
     */
    public function test_failed_if_template_not_found()
    {
        $expect = 'InvalidArgumentException';

        $this->doSetExpectedException($expect);

        // Attempt to render a non-existent template ---
        $this->renderer->render('hello');
        // ---------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_template_rendered()
    {
        $expect = 'This is a text from a template.';

        // Render the test template ---
        $actual = $this->renderer->render('test');
        // ----------------------------

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

        // Render the template with data ---
        $data = array('name' => 'template');

        $actual = $this->renderer->render('test-with-data', $data);
        // ----------------------------------

        // Verify the rendered output matches ---
        $this->assertEquals($expect, $actual);
        // --------------------------------------
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $paths = array(__DIR__ . '/../../Fixture/Templates');

        $this->renderer = new Renderer($paths);
    }
}
