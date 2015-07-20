<?php namespace Rougin\Slytherin;

use Rougin\Slytherin\Uri;
use Twig_Environment;
use Twig_Loader_Filesystem;

/**
 * View Class
 *
 * @package Slytherin
 */
class View
{
	/**
	 * Render the specified view file to the browser
	 * 
	 * @param  string  $view
	 * @param  array   $data
	 */
	public static function render($view, $data = array())
	{
		$loader  = new Twig_Loader_Filesystem(APPPATH . 'views');
		$twig    = new Twig_Environment($loader);

		$data['session']    = (isset($_SESSION)) ? $_SESSION : array();
		$data['uriSegment'] = Uri::get();

		return $twig->render($view . '.php', $data);
	}
}