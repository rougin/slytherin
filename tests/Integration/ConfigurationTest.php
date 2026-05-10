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
    public function test_passed_if_array_value_retrieved()
    {
        // Set configuration with array data ---
        $names = array('John Doe', 'Mary Doe');

        $data = array('names' => $names);

        $expect = $data['names'];

        $config = new Configuration($data);
        // ------------------------------------

        // Verify the array value is returned correctly ---
        $actual = $config->get('names');

        $this->assertEquals($expect, $actual);
        // ------------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_default_value_used()
    {
        // Set configuration with a numeric value ---
        $data = array('number' => 0);

        $expect = $data['number'];

        $config = new Configuration($data);
        // ------------------------------------------

        // Verify the numeric value is returned ---
        $actual = $config->get('number', 1);

        $this->assertEquals($expect, $actual);
        // ----------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_dot_notation_retrieved()
    {
        // Set configuration with nested array data ---
        $database = array('name' => 'test');

        $database['username'] = 'root';

        $data = array('database' => $database);

        $expect = $data['database']['username'];

        $config = new Configuration($data);
        // --------------------------------------------

        // Verify the value is returned via dot notation ---
        $actual = $config->get('database.username');

        $this->assertEquals($expect, $actual);
        // -------------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_empty_directory_loaded()
    {
        // Create an empty temporary directory ------
        $temp = sys_get_temp_dir();

        $path = $temp . '/slyth_' . uniqid('', true);

        mkdir($path);
        // ------------------------------------------

        // Load configuration from the empty directory ---
        $config = new Configuration;

        $result = $config->load($path);
        // -----------------------------------------------

        // Verify the result is empty ---
        rmdir($path);

        $this->assertEmpty($result);
        // ------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_empty_key_ignored()
    {
        // Set a value with an empty key ---
        $config = new Configuration;

        $config->set('', 'value');
        // --------------------------------

        // Verify the empty key returns empty ---
        $this->assertEmpty($config->get(''));
        // --------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_keyword_retrieved()
    {
        // Set configuration with string data ---
        $data = array('name' => 'John Doe');

        $expect = $data['name'];

        $config = new Configuration($data);
        // -------------------------------------

        // Verify the string value is returned correctly ---
        $actual = $config->get('name');

        $this->assertEquals($expect, $actual);
        // -------------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_null_returned_for_missing_key()
    {
        $config = new Configuration;

        $this->assertNull($config->get('name'));
    }
}
