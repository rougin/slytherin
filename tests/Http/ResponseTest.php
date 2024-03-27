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
    protected function doSetUp()
    {
        $this->response = new Response;
    }

    /**
     * @return void
     */
    public function test_getting_the_reason_phrase()
    {
        $expected = 'Lorem ipsum dolor';

        $response = $this->response->withStatus(200, $expected);

        $actual = $response->getReasonPhrase();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_getting_the_status_code()
    {
        $expected = (int) 500;

        $response = $this->response->withStatus($expected);

        $actual = $response->getStatusCode();

        $this->assertEquals($expected, $actual);
    }
}
