<?php if ( ! defined('SYSTEM')) exit('No direct script access allowed');

require APPLICATION . 'config/config.php';
require SYSTEM . 'controller.php';
require SYSTEM . 'model.php';

class Slytherin
{

	public $controller = NULL;
	public $method = 'index';
	public $parameters = NULL;
	public $url = NULL;
	public $segments = NULL;

	public function __construct()
	{
		global $routes;
		$this->controller = $routes['default_controller'];
		$this->segments = $this->strip_url();
		if(isset($this->segments[0]) && $this->segments[0] != NULL) {
			$this->controller = $this->segments[0];
		}
		if(isset($this->segments[1]) && $this->segments[1] != NULL) {
			$this->method = $this->segments[1];
		}
		$this->get($this->controller, $this->method, APPLICATION . 'controllers/' . $this->controller . '.php');
	}

	public function get($controller, $method, $path)
	{
		if (file_exists($path)) {
			require_once($path);
			$baseController = new Controller();
			$controller = new $controller();		
			// if (is_subclass_of($controller, 'Controller'))
			// {
				if (method_exists($controller, $method)) {
					$parameters = new ReflectionMethod($controller, $method);
					$segments = count($this->segments) - 2;
					if ($segments < 0) {
						$segments = 0;
					}
					call_user_method_array($method, $controller, array_splice($this->segments, 2));
				}
				else {
					echo '\'', $method, '\' method not found';
				}
			// }
			// else
			// {
			// 	echo 'The controller is not a subclass of \'Controller\' base class';
			// }
		}
		else {
			echo '\'', $controller, '\' controller not found';
		}
	}

	public function strip_url()
	{
		$request_url = (isset($_SERVER['REQUEST_URI'])) ? $_SERVER['REQUEST_URI'] : NULL;
		$script_url = (isset($_SERVER['PHP_SELF'])) ? $_SERVER['PHP_SELF'] : NULL;
		if($request_url != $script_url) {
			$index = str_replace('index.php', NULL, $script_url);
			$this->url = trim(preg_replace('/' . str_replace('/', '\/', $index) . '/', NULL, $request_url, 1), '/');
		}
		return explode('/', $this->url);
	}

}

$application = new Slytherin();