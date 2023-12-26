<?php

namespace Rougin\Slytherin\Integration;

use Rougin\Slytherin\Testcase;

/**
 * Configuration Test
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class ConfigurationTest extends Testcase
{
    /**
     * Tests Configuration::get with array.
     *
     * @return void
     */
    public function testGetMethodWithArray()
    {
        $data = array('names' => array('John Doe', 'Mary Doe'));

        $expected = (array) $data['names'];

        $config = new Configuration($data);

        $actual = $config->get('names');

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests Conguration::get with default value.
     *
     * @return void
     */
    public function testGetMethodWithDefaultValue()
    {
        $config = new Configuration;

        $this->assertNull($config->get('name'));
    }

    /**
     * Tests Configuration::get with dot notation.
     *
     * @return void
     */
    public function testGetMethodWithDotNotation()
    {
        $data = array('database' => array());

        $data['database'] = array('name' => 'test', 'username' => 'root');

        $expected = $data['database']['username'];

        $config = new Configuration($data);

        $actual = $config->get('database.username');

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests Configuration::get with integer and default value.
     *
     * @return void
     */
    public function testGetMethodWithIntegerAndDefaultValue()
    {
        list($data, $default) = array(array('number' => 0), 1);

        $expected = (integer) $data['number'];

        $config = new Configuration($data);

        $actual = $config->get('number', $default);

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests Configuration::get with string.
     *
     * @return void
     */
    public function testGetMethodWithString()
    {
        $data = array('name' => 'John Doe');

        $expected = $data['name'];

        $config = new Configuration($data);

        $actual = $config->get('name');

        $this->assertEquals($expected, $actual);
    }
}
