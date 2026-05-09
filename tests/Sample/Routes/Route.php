<?php

namespace Rougin\Slytherin\Sample\Routes;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Route
{
    /**
     * @var \Psr\Http\Message\ServerRequestInterface
     */
    protected $request;

    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    protected $response;

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface      $response
     */
    public function __construct(ServerRequestInterface $request, ResponseInterface $response)
    {
        $this->request = $request;

        $this->response = $response;
    }

    /**
     * @param mixed   $data
     * @param integer $code
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function json($data, $code = 200)
    {
        $response = $this->response->withStatus($code);

        $stream = json_encode($data);

        if ($stream !== false)
        {
            $response->getBody()->write($stream);
        }

        $value = 'application/json';

        $name = 'Content-Type';

        return $response->withHeader($name, $value);
    }
}
