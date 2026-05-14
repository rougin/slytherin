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
     * @var boolean
     */
    protected $isV2 = false;

    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    protected $self;

    /**
     * @return void
     */
    public function test_failed_if_status_code_invalid()
    {
        $expect = 'InvalidArgumentException';

        $this->doExpectException($expect);

        // Attempt to set an invalid status code ---
        $this->self->withStatus(600);
        // -----------------------------------------
    }

    /**
     * @return void
     */
    public function test_failed_if_status_code_is_below_100()
    {
        $expect = 'InvalidArgumentException';

        $this->doExpectException($expect);

        $this->self->withStatus(99);
    }

    /**
     * @return void
     */
    public function test_failed_if_status_code_is_not_integer()
    {
        $expect = $this->isV2 ? 'TypeError' : 'InvalidArgumentException';

        $this->doExpectException($expect);

        $this->self->withStatus('foobar');
    }

    /**
     * @return void
     */
    public function test_passed_if_reason_phrase_found()
    {
        $expect = 'Lorem ipsum dolor';

        // Set the status with a custom reason phrase ---
        $self = $this->self->withStatus(200, $expect);
        // ----------------------------------------------

        // Verify the reason phrase is returned correctly ---
        $actual = $self->getReasonPhrase();

        $this->assertEquals($expect, $actual);
        // -------------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_status_code_found()
    {
        $expect = 500;

        // Set the status code on the response ---
        $self = $this->self->withStatus($expect);
        // ---------------------------------------

        // Verify the status code is returned correctly ---
        $actual = $self->getStatusCode();

        $this->assertEquals($expect, $actual);
        // ------------------------------------------------
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->isV2 = Interop::isVersion2();

        $this->self = new Response;
    }
}
