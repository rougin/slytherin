<?php

namespace Rougin\Slytherin\Http;

use Http\HttpResponse;
use Rougin\Slytherin\Http\ResponseInterface;

/**
 * Response
 *
 * A simple implementation of a HTTP response library built on top of
 * Patrick Louys' HTTP Component.
 *
 * https://github.com/PatrickLouys/http
 * 
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Response implements ResponseInterface
{
    protected $response;

    /**
     * @param HttpResponse $response
     */
    public function __construct(HttpResponse $response)
    {
        $this->response = $response;
    }

    /**
     * Sets the body content.
     * 
     * @param  string $content
     * @return void
     */
    public function setContent($content)
    {
        // Return the list of headers if any
        if (is_array($this->response->getHeaders())) {
            foreach ($this->response->getHeaders() as $header) {
                header($header);
            }
        }

        return $this->response->setContent($content);
    }

    /**
     * Sets the HTTP status code.
     * 
     * @param  integer $code
     * @param  string  $text
     * @return void
     */
    public function setStatusCode($code, $text = null)
    {
        return $this->response->setStatusCode($code, $text);
    }

    /**
     * Returns the body content.
     * 
     * @return string
     */
    public function getContent()
    {
        return $this->response->getContent();
    }

    /**
     * Returns an array with the HTTP headers.
     * 
     * @return array
     */
    public function getHeaders()
    {
        return $this->response->getHeaders();
    }

    /**
     * Sets the headers for a redirect.
     * 
     * @param  string $url
     * @return void
     */
    public function redirect($url)
    {
        return $this->response->redirect($url);
    }
}
