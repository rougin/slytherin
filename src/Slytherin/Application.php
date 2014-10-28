<?php

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

		/**
		 * Autoload controller files
		 */

		spl_autoload_register(function ($class) {
			$controller = 'app/controllers/' . $class . '.php';
			$library    = 'app/libraries/' . $class . '.php';
			$model      = 'app/models/' . $class . '.php';

			if (file_exists($controller)) {
				include $controller;
			} elseif (file_exists($library)) {
				include $library;
			} elseif (file_exists($model)) {
				include $model;
			}
		});
	}

	/**
	 * Run the application
	 * 
	 * @return object
	 */
	public function run()
	{
		$this->_checkDirectories();
		$this->_checkMethod();
		$controller = $this->_loadController();

		/**
		 * Run the specified parameters
		 */
		
		echo call_user_method_array($this->_router->getMethod(), $controller, array_splice($this->_router->getParameters(), 0));
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
				return trigger_error('"' . $directory . '" cannot be found');
			}
		}
	}

	/**
	 * Check if the method really exists from the specified controller
	 * 
	 * @return error
	 */
	protected function _checkMethod()
	{
		if ( ! method_exists($this->_router->getController(), $this->_router->getMethod())) {
			return trigger_error('"' . $this->_router->getMethod() . '" method cannot be found from the "' . $this->_router->getController() . '" controller');
		}
	}

	/**
	 * Load the specified controller
	 * 
	 * @return error | object
	 */
	protected function _loadController()
	{
		$controllerFile        = 'app/controllers/' . $this->_router->getController() . '.php';
		$constructorParameters = array();

		if ( ! file_exists($controllerFile)) {
			return trigger_error('"' . $this->_router->getController() . '" controller cannot be found from the "app/controllers/" directory');
		}

		/**
		 * Create a new Reflection Class and get its constructor
		 */

		$class = new \ReflectionClass($this->_router->getController());

		/**
		 * Check if the controller is extended to the base controller
		 */

		if ( ! $class->getParentClass() || ($class->getParentClass()->name != 'Controller' && $class->getParentClass()->name != 'Slytherin\Controller')) {
			return trigger_error('Class "' . $this->_router->getController() . '" must be extended to a "Controller" class');
		}

		$constructor = $class->getConstructor();

		foreach ($constructor->getParameters() as $parameter) {
			/**
			 * Get the class name without needing the class to be loaded
			 */
			
			preg_match('/\[\s\<\w+?>\s([\w]+)/s', $parameter->__toString(), $matches);
			$object = isset($matches[1]) ? $matches[1] : NULL;

			if ($object) {
				$constructorParameters[] = new $object();
			}
		}

		return $class->newInstanceArgs($constructorParameters);
	}

}