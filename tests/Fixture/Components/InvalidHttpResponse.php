<?php

namespace Rougin\Slytherin\Fixture\Components;

use Rougin\Slytherin\Fixture\Classes\NewClass;
use Rougin\Slytherin\Http\ServerRequest;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class InvalidHttpResponse extends HttpComponent
{
    /**
     * @return mixed
     */
    public function get()
    {
        $server = array('REQUEST_METHOD' => 'GET', 'REQUEST_URI' => '/', 'SERVER_NAME' => 'localhost', 'SERVER_PORT' => '8000');

        return array(new ServerRequest($server), new NewClass);
    }
}
