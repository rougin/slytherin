<?php

namespace Rougin\Slytherin\Http;

/**
 * Response Test
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class ResponseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    protected $response;

    /**
     * Sets up the response.
     *
     * @return void
     */
    public function setUp()
    {
        $this->response = new \Rougin\Slytherin\Http\Response('1.1', []);
    }

    /**
     * Tests getReasonPhrase().
     *
     * @return void
     */
    public function testGetReasonPhrase()
    {
        $expected = 'Lorem ipsum dolor';
        $response = $this->response->withStatus(200, $expected);

        $this->assertEquals($expected, $response->getReasonPhrase());
    }
}
