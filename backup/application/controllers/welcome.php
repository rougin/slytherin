<?php if ( ! defined('SYSTEM')) exit('No direct script access allowed');

/**
 * Slytherin - Yet another barebones web framework for PHP
 *
 * @author     Rougin Royce Gutib <rougin.royce@gmail.com>
 * @package    Slytherin
 */

/**
 * Welcome Controller Class
 */
class Welcome extends Controller
{

	/**
	 * Load the view with the filename of "index.php"
	 * from the "application/views/welcome" folder
	 * 
	 * @return object
	 */
	public function index()
	{
		$data['name'] = 'rougin';

		return View::create('welcome/index', $data);
	}

}
