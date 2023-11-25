<?php

namespace Rougin\Slytherin\Forward\Fixture\Routes;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Home extends Route
{
    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function index()
    {
        return $this->response->withStatus(404);
    }
}