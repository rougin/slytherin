<?php

namespace Rougin\Slytherin\Http;

use Http\HttpRequest;
use Rougin\Slytherin\Http\RequestInterface;

/**
 * Request
 *
 * A simple implementation of a HTTP request library built on top of
 * Patrick Louys' HTTP Component.
 *
 * https://github.com/PatrickLouys/http
 * 
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Request implements RequestInterface
{
	protected $request;

	/**
	 * @param HttpRequest $request
	 */
	public function __construct(HttpRequest $request)
	{
		$this->request = $request;
	}

	/**
     * Which request method was used to access the page:
     * i.e. 'GET', 'HEAD', 'POST', 'PUT'
     * 
     * @return string
     */
	public function getMethod()
	{
		return $this->request->getMethod();
	}

	/**
     * Returns just the path.
     * 
     * @return string
     */
	public function getPath()
	{
		return $this->request->getPath();
	}

	/**
     * Returns a parameter value or a default value if none is set.
     * 
     * @param  string $key
     * @param  string $defaultValue (optional)
     * @return string
     */
	public function getParameter($key, $defaultValue = NULL)
	{
		return $this->request->getParameter($key, $defaultValue);
	}
}
