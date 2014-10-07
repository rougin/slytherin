<?php if ( ! defined('SYSTEM')) exit('No direct script access allowed');

/**
 * Slytherin - Yet another barebones web framework for PHP
 *
 * @author     Rougin Royce Gutib <rougin.royce@gmail.com>
 * @package    Slytherin
 */

/**
 * Load the PDO Database Active Record Class
 */
require SYSTEM . 'database.php';

/**
 * Base Model
 */
class Model extends Database
{
	public static function all()
	{
		return 'asdasdsa';
	}

	public static function load($file)
	{
		require APPLICATION . 'models/' . $file . '.php';
	}

}