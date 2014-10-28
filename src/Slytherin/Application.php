<?php

namespace Slytherin;

use Slytherin\Controller;
use Slytherin\View;

/**
 * The Slytherin Application
 */

class Application
{

	protected $_router = NULL;

	/**
	 * Load the necessary configurations
	 */
	public function __construct(Router $router)
	{
		$this->_router = $router;
	}

	/**
	 * Run the application
	 */
	public function run()
	{
		$baseController = new Controller();

		$this->_checkDirectories();
		$this->_loadController();
		$this->_checkMethod();

		/**
		 * Run the specified parameters
		 */
		
		echo call_user_func_array(array($this->_router->getController(), $this->_router->getMethod()), array_splice($this->_router->getParameters(), 0));
	}

	/**
	 * Check if the directories are already existed
	 * 
	 * @return error
	 */
	protected function _checkDirectories()
	{
		$directories = array(
			'app/',
			'app/controllers/',
			'app/models/',
			'app/views/'
		);

		foreach ($directories as $directory) {
			if (! file_exists($directory)) {
				return View::error('"' . $directory . '" cannot be found');
			}
		}
	}

	/**
	 * Load the specified controller
	 * 
	 * @return error | object
	 */
	protected function _loadController()
	{
		$controllerFile = 'app/controllers/' . $this->_router->getController() . '.php';

		if ( ! file_exists($controllerFile)) {
			return View::error('"' . $this->_router->getController() . '" controller cannot be found from the "app/controllers/" directory');
		}

		$controllerName = $this->_router->getController();

		return new $controllerName;
	}

	/**
	 * Check if the method really exists from the specified controller
	 * 
	 * @return error
	 */
	protected function _checkMethod()
	{
		if ( ! method_exists($this->_router->getController(), $this->_router->getMethod())) {
			return View::error('"' . $this->_router->getMethod() . '" method cannot be found from the "' . $this->_router->getController() . '" controller');
		}
	}

}