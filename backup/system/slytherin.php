<?php if ( ! defined('SYSTEM')) exit('No direct script access allowed');

/**
 * Slytherin - Yet another barebones web framework for PHP
 *
 * @author     Rougin Royce Gutib <rougin.royce@gmail.com>
 * @package    Slytherin
 */

/**
 * Load the base model class
 */
require SYSTEM . 'model.php';

/**
 * Load the class for loading the views
 */
require SYSTEM . 'view.php';

/**
 * Load the customized error handler
 */
require SYSTEM . 'error.php';

/**
 * Slytherin Core File
 *
 * Loads the base classes and launch the specified request
 */
class Slytherin
{

	private $controller;
	private $method;
	private $parameters;

	/**
	 * Launch the application
	 */
	public function launch()
	{
		/**
		 * Load the controllers and call the specified method
		 */
		$this->controller = $this->load_controller();
		$this->method = $this->call_method();

		/**
		 * Launch the specified parameters
		 */
		echo call_user_func_array(array($this->controller, $this->method), array_splice($this->parameters, 0));
	}

	/**
	 * Call the requested method from the specified controller
	 * 
	 * @return object
	 */
	private function call_method()
	{
		$url = $this->get_parameters();

		/**
		 * If URL has the parameters to call the method
		 */
		if (isset($url->method) && $url->method != '') {
			$method = strtok($url->method, '?');
		} else {
			$method = strtok('index', '?');
		}
		
		/**
		 * If the specified controller has that specified method
		 */
		if (method_exists($this->controller, $method)) {
			return $method;
		} else {
			return exit('No methods/functions found.');
		}
	}

	/**
	 * Get the parameters from the URL
	 * 
	 * @return object
	 */
	private function get_parameters()
	{
		/**
		 * Get the full URL link
		 */
		$full_url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

		/**
		 * Strip the Base URL from the Full URL
		 */
		$url = str_replace(URL, '', $full_url);

		/**
		 * Strip the controller and method (if available) in the URL
		 */
		$temp_url = array_filter(explode('/', $url));

		/**
		 * Assign the controller and method from the URL
		 */
		$url = (object) array();

		if (isset($temp_url[0])) {
			$url->controller = $temp_url[0];
			unset($temp_url[0]);
		}

		if (isset($temp_url[1])) {
			$url->method = $temp_url[1];
			unset($temp_url[1]);
		}

		/**
		 * Treat the remaining indices as parameters
		 */
		$this->parameters = $temp_url;

		/**
		 * Return the object result
		 */
		return $url;
	}

	/**
	 * Load the application controller and
	 * the specified controller from the URL
	 * 
	 * @return class
	 */
	private function load_controller()
	{
		/**
		 * Load the base controller
		 */
		require SYSTEM . 'controller.php';
		/**
		 * Load the routes.php
		 */
		require APPLICATION . 'config/routes.php';
		
		/**
		 * Get the URL parameters
		 */
		$url = $this->get_parameters();

		/**
		 * If URL has parameters to call the controller
		 */
		if (isset($url->controller) && $url->controller != '') {
			/**
			 * Get the specified controller
			 */
			$controller = APPLICATION . 'controllers/' . $url->controller . '.php';
		} else {
			/**
			 * Get the default controller in the routes.php
			 */
			$controller = APPLICATION . 'controllers/' . $route['default'] . '.php';
		}

		/**
		 * If the specified controller file exists
		 */
		if (file_exists($controller)) {
			require $controller;

			/**
			 * Create a new class for the specified controller
			 */
			return new $url->controller;
		} else {

			/**
			 * Exit the application with an error
			 */
			return exit('Unable to load your default controller. Please make sure the controller specified in your "routes.php" file is valid.');
		}
	}

}