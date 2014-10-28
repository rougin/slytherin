<?php

namespace Slytherin;

/**
 * Routing Class
 */

class Router
{

	protected $_controller;
	protected $_method;
	protected $_parameters;

	/**
	 * Parse the URL to segments
	 */
	public function __construct()
	{
		/**
		 * Set the default configuration
		 */
		
		$this->_method     = 'index';
		$this->_parameters = array(); 

		$this->_defineUrls();

		/**
		 * Seperate the links from the string difference of BASE_URL and CURRENT_URL
		 */

		$segments = explode('/', str_replace(BASE_URL, '', CURRENT_URL));

		$this->_controller = $segments[0];
		$this->_method     = ( ! array_key_exists(1, $segments) || $segments[1] == NULL) ? $this->_method : $segments[1];

		unset($segments[0]);
		unset($segments[1]);

		$this->_parameters = $segments;
	}

	/**
	 * Get the controller name
	 * 
	 * @return string
	 */
	public function getController()
	{
		return ucfirst($this->_controller);
	}

	/**
	 * Get the method name
	 * 
	 * @return string
	 */
	public function getMethod()
	{
		return $this->_method;
	}

	/**
	 * Get the remaining parameters
	 * 
	 * @return array
	 */
	public function getParameters()
	{
		return $this->_parameters;
	}

	/**
	 * Define the base and current urls
	 */
	protected function _defineUrls()
	{
		if (isset($_SERVER['HTTP_HOST'])) {
			$baseUrl = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST']
				. substr($_SERVER['SCRIPT_NAME'], 0, -strlen(basename($_SERVER['SCRIPT_NAME'])));
		} else {
			$baseUrl = 'http://localhost/';
		}

		define('BASE_URL', $baseUrl);
		define('CURRENT_URL', 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
	}

}