<?php

namespace Rougin\Slytherin\Http;

/**
 * Response Test
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class ResponseTest extends \LegacyPHPUnit\TestCase
{
    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    protected $response;

    /**
     * Sets up the response instance.
     *
     * @return void
     */
    protected function doSetUp()
    {
        $this->response = new Response;
    }

    /**
     * Tests ResponseInterface::getReasonPhrase.
     *
     * @return void
     */
    public function testGetReasonPhraseMethod()
    {
        $expected = 'Lorem ipsum dolor';

        $response = $this->response->withStatus(200, $expected);

        $result = $response->getReasonPhrase();

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests ResponseInterface::getStatusCode.
     *
     * @return void
     */
    public function testGetStatusCodeMethod()
    {
        $expected = (integer) 500;

        $response = $this->response->withStatus($expected);

        $result = $response->getStatusCode();

        $this->assertEquals($expected, $result);
    }
}
