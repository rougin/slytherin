<?php

namespace Rougin\Slytherin\Sample\Routes;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rougin\Slytherin\Sample\Depots\TestDepot;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Hello extends Route
{
    /**
     * @return string
     */
    public function conts()
    {
        /** @var array<string, string> */
        $data = $this->request->getParsedBody();

        return 'Hello, ' . $data['name'] . '!';
    }

    /**
     * @param  \Rougin\Slytherin\Sample\Depots\TestDepot $test
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function index(TestDepot $test)
    {
        return $test->text('Hello world!');
    }

    /**
     * @param  string                                    $name
     * @param  \Rougin\Slytherin\Sample\Depots\TestDepot $test
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function name($name, TestDepot $test)
    {
        return $test->text("Hello, $name!");
    }

    /**
     * @return string
     */
    public function param(ServerRequestInterface $request)
    {
        /** @var array<string, string> */
        $data = $request->getParsedBody();

        return 'Hello, ' . $data['name'] . '!';
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function response(ResponseInterface $response)
    {
        return $response;
    }

    /**
     * @return string
     */
    public function string()
    {
        return 'This is a simple string.';
    }

    /**
     * @return string
     */
    public function upload(ServerRequestInterface $request)
    {
        $files = $request->getUploadedFiles();

        /** @var \Psr\Http\Message\UploadedFileInterface */
        $selected = $files['files'][0];

        $name = $selected->getClientFilename();

        return 'The file is ' . $name . '!';
    }

    /**
     * @return string
     */
    public function world()
    {
        return 'Hello string world!';
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function encoded()
    {
        return $this->json('Encoded world!');
    }
}
