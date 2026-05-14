<?php

namespace Rougin\Slytherin\Template;

use Rougin\Slytherin\Testcase;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class RendererTestCases extends Testcase
{
    /**
     * @var \Rougin\Slytherin\Template\RendererInterface
     */
    protected $self;

    /**
     * @return void
     */
    public function test_failed_if_template_not_found()
    {
        $expect = 'InvalidArgumentException';

        $this->doExpectException($expect);

        $this->self->render('hello');
    }

    /**
     * @return void
     */
    public function test_passed_if_template_rendered()
    {
        $expect = 'This is a text from a template.';

        $actual = $this->self->render('test');

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_template_rendered_with_data()
    {
        $expect = 'This is a text from a template.';

        // Render the template with data -----------
        $data = array('name' => 'template');

        $file = 'test-with-data';

        $actual = $this->self->render($file, $data);
        // -----------------------------------------

        $this->assertEquals($expect, $actual);
    }
}
