<?php namespace Rougin\Slytherin;

use Pux\Executor;
use Pux\Mux;

/**
 * Application Class
 *
 * @package Slytherin
 */
class Application {

	protected $_constructorArguments = array();

	protected $_controller = NULL;
	protected $_methods    = array();
	protected $_router     = NULL;

	/**
	 * Load the necessary configurations
	 */
	public function __construct(Mux $router)
	{
		$this->_defineUrls();
		
		$this->_router = $router;
	}

	/**
	 * Run the application
	 */
	public function run()
	{
		$this->_getController();
		
		$route = $this->_router;

		/**
		 * Include the user's specified routes
		 */

		include 'app/config/routes.php';

		foreach ($this->_methods as $method => $parameters) {
			if ($method == '__construct') continue;

			$options = array();

			if ( ! empty($this->_constructorArguments)) {
				$options['constructor_args'] = $this->_constructorArguments;
			}

			$regex    = array();
			$segments = NULL;

			/**
			 * Implode the parameters and create a regex pattern
			 */
			
			if (is_array($parameters)) {
				foreach ($parameters as $parameter => $defaultValue) {
					$segments .= '/:' . $parameter;

					$regex[$parameter] = '\w+';
					$regex[$parameter] = (gettype($defaultValue) == 'integer') ? '\d+' : $regex[$parameter];
				}

				/**
				 * Get the options
				 */

				$options['default'] = $parameters;
				$options['require'] = $regex;
			}

			/**
			 * Add an additional pattern for 'create' and 'edit' methods
			 */

			$pattern = '/' . $this->_controller . '/' . $method . $segments;

			/**
			 * Define the specified route
			 */

			$source = 'Controllers\\' . ucfirst($this->_controller) . ':' . $method;

			/**
			 * Set the HTTP verb for the specified method
			 */

			/**
			 * Add a new route if the method is index
			 */

			if ($hasIndex) {
				$route->get(str_replace('/index', '', $pattern), $source, $options);
				$hasIndex = FALSE;

				$routes[] = array(
					'pattern' => str_replace('/index', '', $pattern),
					'source' => $source,
					'options' => $options
				);
			}

			switch ($method) {
				case 'delete':
					$route->delete($pattern, $source, $options);
					break;
				case 'post':
				case 'update':
					$route->post($pattern, $source, $options);
					break;
				case 'put':
				case 'store':
					$route->put($pattern, $source, $options);
					break;
				default:
					$route->get($pattern, $source, $options);
					break;
			}

			$routes[] = array(
				'pattern' => $pattern,
				'source' => $source,
				'options' => $options
			);
		}

		/**
		 * Set the URL to be dispatch
		 */

		$url = str_replace(BASE_URL, '', CURRENT_URL);
		$url = (substr($url, -1) == '/') ? substr($url, 0, strlen($url) - 1) : $url;
		$url = '/' . strtok($url, '?');

		/**
		 * Dispatch and execute the route
		 */

		echo Executor::execute($route->dispatch($url));
	}

	/**
	 * Define the base and current urls
	 */
	protected function _defineUrls()
	{
		$baseUrl    = 'http://localhost/';
		$isHttps    = (isset($_SERVER['HTTPS'])) ? 's' : '';
		$scriptName = substr($_SERVER['SCRIPT_NAME'], 0, -strlen(basename($_SERVER['SCRIPT_NAME'])));

		/**
		 * Get the base url from the $_SERVER['HTTP_HOST']
		 */

		if (isset($_SERVER['HTTP_HOST'])) {
			$baseUrl = 'http' . $isHttps . '://' . $_SERVER['HTTP_HOST'] . $scriptName;
		}

		/**
		 * Check the HTTP method from the form
		 */

		if (isset($_POST['_method']) && ($_POST['_method'] == 'PUT' || $_POST['_method'] == 'DELETE')) {
			$_SERVER['REQUEST_METHOD'] = $_POST['_method'];
			unset($_POST['_method']);
		}

		/**
		 * Define the following URLs
		 */

		define('BASE_URL', $baseUrl);
		define('CURRENT_URL', 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
	}

	/**
	 * Check the controller and get its contents
	 *
	 * @return boolean
	 */
	protected function _getController()
	{
		/**
		 * Seperate the links from the string difference of BASE_URL and CURRENT_URL
		 */

		$segments = explode('/', str_replace(BASE_URL, '', CURRENT_URL));

		/**
		 * Set the first index as the controller
		 */

		if (empty($segments[0])) {
			return 0;
		}

		$controller        = '\\Controllers\\' . ucfirst(strtok($segments[0], '?'));
		$this->_controller = strtok($segments[0], '?');

		try {
			$class = new \ReflectionClass($controller);
		} catch (\ReflectionException $exception) {
			return 0;
		}

		$constructor = $class->getConstructor();

		if ($constructor && count($constructor->getParameters()) != 0) {
			foreach ($constructor->getParameters() as $parameter) {
				/**
				 * Get the class name without needing the class to be loaded
				 */
				
				preg_match('/\[\s\<\w+?>\s([\w]+)/s', $parameter->__toString(), $matches);
				$object = isset($matches[1]) ? $matches[1] : NULL;

				if ($object) {
					$this->_constructorArguments[] = new $object();
				}
			}
		}

		foreach ($class->getMethods() as $method) {
			if (! $method->isPublic()) {
				continue;
			}

			/**
			 * Add the curent method to the list of methods
			 */

			$this->_methods[$method->name] = NULL;

			/**
			 * Get the parameters for the each specified method
			 */

			foreach ($method->getParameters() as $parameter) {
				$this->_methods[$method->name][$parameter->name] = NULL;

				if ($parameter->isOptional()) {
					$this->_methods[$method->name][$parameter->name] = $parameter->getDefaultValue();
				}
			}
		}
	}

}