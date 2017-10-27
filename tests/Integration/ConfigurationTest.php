<?php

namespace Rougin\Slytherin\Integration;

/**
 * Configuration Test
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests Configuration::get with string.
     *
     * @return void
     */
    public function testGetMethodWithString()
    {
        $data = array('name' => 'John Doe');

        $config = new Configuration($data);

        $this->assertEquals($data['name'], $config->get('name'));
    }

    /**
     * Tests Configuration::get with integer and default value.
     *
     * @return void
     */
    public function testGetMethodWithIntegerAndDefaultValue()
    {
        list($data, $default) = array(array('number' => 0), 1);

        $config = new Configuration($data);

        $this->assertEquals($data['number'], $config->get('number', $default));
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
     * Tests Configuration::get with array.
     *
     * @return void
     */
    public function testGetMethodWithArray()
    {
        $data = array('names' => array('John Doe', 'Mary Doe'));

        $config = new Configuration($data);

        $this->assertEquals($data['names'], $config->get('names'));
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

        $config = new Configuration($data);

        $this->assertEquals($data['database']['username'], $config->get('database.username'));
    }
}
