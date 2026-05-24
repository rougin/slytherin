<?php

namespace Rougin\Slytherin\Fixture\Components;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rougin\Slytherin\Component\AbstractComponent;
use Rougin\Slytherin\Component\Collection;
use Rougin\Slytherin\Http\Response;
use Rougin\Slytherin\Http\ServerRequest;
use Rougin\Slytherin\System\Errors\ComponentNotArray;
use Rougin\Slytherin\System\Errors\RequestNotFound;
use Rougin\Slytherin\System\Errors\ResponseNotFound;

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

    /**
     * @param \Rougin\Slytherin\Component\Collection $collection
     *
     * @return void
     */
    public function register(Collection $collection)
    {
        $result = $this->get();

        if (! is_array($result))
        {
            throw new ComponentNotArray;
        }

        $request = $result[0];

        if (! $request instanceof ServerRequestInterface)
        {
            throw new RequestNotFound($request);
        }

        $response = $result[1];

        if (! $response instanceof ResponseInterface)
        {
            throw new ResponseNotFound($response);
        }

        $collection->setHttp($request, $response);
    }
}
