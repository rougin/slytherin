<?php

namespace Rougin\Slytherin\Http;

use Rougin\Slytherin\Testcase;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class ResponseTest extends Testcase
{
    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    protected $response;

    /**
     * @return void
     */
    public function test_failed_if_status_code_invalid()
    {
        $expect = 'InvalidArgumentException';

        $this->doSetExpectedException($expect);

        // Attempt to set an invalid status code ---
        $this->response->withStatus(600);
        // -----------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_reason_phrase_retrieved()
    {
        $expect = 'Lorem ipsum dolor';

        // Set the status with a custom reason phrase ----
        $response = $this->response->withStatus(200, $expect);
        // -----------------------------------------------

        // Verify the reason phrase is returned correctly ---
        $actual = $response->getReasonPhrase();

        $this->assertEquals($expect, $actual);
        // -------------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_status_code_retrieved()
    {
        $expect = 500;

        // Set the status code on the response -----
        $response = $this->response->withStatus($expect);
        // -----------------------------------------

        // Verify the status code is returned correctly ---
        $actual = $response->getStatusCode();

        $this->assertEquals($expect, $actual);
        // ------------------------------------------------
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->response = new Response;
    }
}
