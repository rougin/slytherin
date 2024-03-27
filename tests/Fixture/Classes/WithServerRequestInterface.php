<?php

namespace Rougin\Slytherin\Fixture\Classes;

use Psr\Http\Message\ServerRequestInterface;

/**
 * With Server Request Interface
 *
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class WithServerRequestInterface
{
    /**
     * @var \Psr\Http\Message\ServerRequestInterface
     */
    protected $request;

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     */
    public function __construct(ServerRequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * Returns a request with the value of "test" query parameter.
     *
     * @return \Psr\Http\Message\ServerRequestInterface
     */
    public function index()
    {
        $query = $this->request->getQueryParams();

        return $query['test'];
    }
}
