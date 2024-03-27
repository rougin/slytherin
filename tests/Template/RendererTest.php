<?php

namespace Rougin\Slytherin\Template;

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
    protected function doSetUp()
    {
        $root = str_replace('Template', 'Fixture', __DIR__);

        $directories = (string) $root . '/Templates';

        $this->renderer = new Renderer($directories);
    }

    /**
     * @return void
     */
    public function test_rendering_a_text_from_file()
    {
        $expected = 'This is a text from a template.';

        $actual = $this->renderer->render('test');

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_rendering_a_text_from_file_with_data()
    {
        $expected = (string) 'This is a text from a template.';

        $data = array('name' => 'template');

        $actual = $this->renderer->render('test-with-data', $data);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_rendering_a_text_from_file_with_an_error()
    {
        $this->setExpectedException('InvalidArgumentException');

        $this->renderer->render('hello');
    }
}
