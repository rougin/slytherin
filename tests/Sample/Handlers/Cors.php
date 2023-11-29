<?php

namespace Rougin\Slytherin\Sample\Handlers;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rougin\Slytherin\Http\Response;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Cors implements MiddlewareInterface
{
    const METHODS = 'Access-Control-Allow-Methods';

    const ORIGIN = 'Access-Control-Allow-Origin';

    /**
     * @var array
     */
    protected $allowed = array();

    /**
     * @var array
     */
    protected $methods = array('GET', 'POST', 'PUT', 'DELETE', 'OPTIONS');

    /**
     * Initializes the middleware instance.
     *
     * @param array|null $allowed
     * @param array|null $methods
     */
    public function __construct(array $allowed = null, array $methods = null)
    {
        $this->allowed($allowed === null ? array('*') : $allowed);

        $this->methods($methods === null ? $this->methods : $methods);
    }

    /**
     * Process an incoming server request and return a response, optionally
     * delegating to the next middleware component to create the response.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface         $request
     * @param  \Interop\Http\ServerMiddleware\DelegateInterface $delegate
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $options = $request->getMethod() === 'OPTIONS';

        $response = $options ? new Response : $delegate->process($request);

        $response = $response->withHeader(self::ORIGIN, $this->allowed);

        return $response->withHeader(self::METHODS, (array) $this->methods);
    }

    /**
     * Sets the allowed URLS.
     *
     * @param  array $allowed
     * @return self
     */
    public function allowed($allowed)
    {
        $this->allowed = $allowed;

        return $this;
    }

    /**
     * Sets the allowed HTTP methods.
     *
     * @param  array $methods
     * @return self
     */
    public function methods($methods)
    {
        $this->methods = $methods;

        return $this;
    }
}
