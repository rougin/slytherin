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
        if (! interface_exists('Psr\Http\Message\ResponseInterface')) {
            $this->markTestSkipped('PSR-7 is not installed.');
        }

        $this->response = new \Rougin\Slytherin\Http\Response;
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
