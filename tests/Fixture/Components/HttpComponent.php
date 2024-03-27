<?php

namespace Rougin\Slytherin\Fixture\Components;

use Rougin\Slytherin\Component\AbstractComponent;
use Rougin\Slytherin\Http\Response;
use Rougin\Slytherin\Http\ServerRequest;

/**
 * HTTP Component
 *
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class HttpComponent extends AbstractComponent
{
    /**
     * Type of the component:
     * container, dispatcher, debugger, http, middleware, template
     *
     * @var string
     */
    protected $type = 'http';

    /**
     * @var \Psr\Http\Message\ServerRequestInterface
     */
    protected $request;

    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    protected $response;

    /**
     * Returns an instance from the named class.
     * It's used in supporting component types for Slytherin.
     *
     * @return mixed
     */
    public function get()
    {
        $slash = DIRECTORY_SEPARATOR;

        $root = str_replace($slash . 'tests' . $slash . 'Fixture' . $slash . 'Components', '', __DIR__);

        $server = array('REQUEST_URI' => '/');
        $server['DOCUMENT_ROOT'] = $root;
        $server['REMOTE_ADDR'] = '127.0.0.1';
        $server['SCRIPT_FILENAME'] = '/var/www/html/slytherin/index.php';
        $server['SCRIPT_NAME'] = '/slytherin/index.php';
        $server['SERVER_NAME'] = 'localhost';
        $server['SERVER_PORT'] = '8000';
        $server['REQUEST_METHOD'] = 'GET';

        $this->request = new ServerRequest($server);

        $this->response = new Response;

        return array($this->request, $this->response);
    }
}
