<?php namespace Rougin\Slytherin;

use Twig_Environment;
use Twig_Loader_Filesystem;

/**
 * View Class
 *
 * @package Slytherin
 */
class View {

	/**
	 * Render the specified view file to the browser
	 * 
	 * @param  string  $view
	 * @param  array   $data
	 */
	public static function render($view, $data = array())
	{
		$loader  = new Twig_Loader_Filesystem(APPPATH . 'views');
		$session = new Session();
		$twig    = new Twig_Environment($loader);

		return $twig->render($view . '.php', $data);
	}

}