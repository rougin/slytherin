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
        $data = array('names' => array('John Doe', 'Mary Doe'));

        $expect = $data['names'];

        $config = new Configuration($data);
        // -----------------------------------

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
        list($data, $default) = array(array('number' => 0), 1);

        // Set configuration with a numeric value ---
        $expect = $data['number'];

        $config = new Configuration($data);
        // ------------------------------------------

        // Verify the numeric value is returned, not the default ---
        $actual = $config->get('number', $default);

        $this->assertEquals($expect, $actual);
        // ----------------------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_dot_notation_retrieved()
    {
        // Set configuration with nested array data ---
        $data = array('database' => array());

        $data['database'] = array('name' => 'test', 'username' => 'root');

        $expect = $data['database']['username'];

        $config = new Configuration($data);
        // --------------------------------------------

        // Verify the nested value is returned via dot notation ---
        $actual = $config->get('database.username');

        $this->assertEquals($expect, $actual);
        // ---------------------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_empty_directory_loaded()
    {
        // Create an empty temporary directory ---
        $path = sys_get_temp_dir() . '/slytherin_empty_' . uniqid('', true);

        mkdir($path);
        // ---------------------------------------

        // Load configuration from the empty directory ---
        $config = new Configuration;

        $result = $config->load($path);
        // ----------------------------------------------

        rmdir($path);

        // Verify the result is empty ---
        $this->assertEmpty($result);
        // -----------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_empty_key_ignored()
    {
        $config = new Configuration;

        // Set a value with an empty key ---
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

        // Verify a missing key returns null ---
        $this->assertNull($config->get('name'));
        // --------------------------------------
    }
}
