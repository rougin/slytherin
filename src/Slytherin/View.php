<?php

/**
 * Slytherin - Yet another barebones web framework for PHP
 *
 * @author     Rougin Royce Gutib <rougin.royce@gmail.com>
 * @package    Slytherin
 */

/**
 * View Class
 *
 * Responsible for loading the files from the "views" folder
 */
class View
{

	/**
	 * Create the view from the specified file
	 * 
	 * @param  string $file
	 * @param  string $data
	 * @return string
	 */
	public static function create($file, $data = NULL)
	{
		/**
		 * Assume the folder location of the file
		 */
		
		$location = APPLICATION . 'views/' . $file . '.php';

		/**
		 * If the parameters is not empty
		 */
		
		if ($data != NULL) {
			/**
			 * Extract the data to the current view
			 */
			
			extract($data);
		}

		require $location;
	}

}