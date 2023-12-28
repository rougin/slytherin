<?php declare(strict_types = 1);

namespace Rougin\Slytherin\Template\Vanilla;

use Rougin\Slytherin\Testcase;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
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
        $paths = array(__DIR__ . '/../../Fixture/Templates');

        $this->renderer = new Renderer($paths);
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
        $expected = 'This is a text from a template.';

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
