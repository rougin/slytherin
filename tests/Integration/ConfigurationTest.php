<?php

namespace Rougin\Slytherin\Integration;

use Rougin\Slytherin\Testcase;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class ConfigurationTest extends Testcase
{
    /**
     * @return void
     */
    public function test_setting_array_as_values()
    {
        $data = array('names' => array('John Doe', 'Mary Doe'));

        $expected = (array) $data['names'];

        $config = new Configuration($data);

        $actual = $config->get('names');

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_getting_keyword_with_value()
    {
        $config = new Configuration;

        $this->assertNull($config->get('name'));
    }

    /**
     * @return void
     */
    public function test_getting_keyword_with_dot_notation()
    {
        $data = array('database' => array());

        $data['database'] = array('name' => 'test', 'username' => 'root');

        $expected = $data['database']['username'];

        $config = new Configuration($data);

        $actual = $config->get('database.username');

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_setting_value_as_a_number_with_default_value()
    {
        list($data, $default) = array(array('number' => 0), 1);

        $expected = (int) $data['number'];

        $config = new Configuration($data);

        $actual = $config->get('number', $default);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_getting_keyword_as_a_string()
    {
        $data = array('name' => 'John Doe');

        $expected = $data['name'];

        $config = new Configuration($data);

        $actual = $config->get('name');

        $this->assertEquals($expected, $actual);
    }
}
