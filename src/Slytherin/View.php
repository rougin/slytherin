<?php namespace Slytherin;

/**
 * View Class
 */

class View
{

	/**
	 * Render the specified view file to the browser
	 * 
	 * @param  string $view
	 * @param  array  $data
	 */
	public static function render($view, $data = NULL)
	{
		/**
		 * Assume the directory of the view file
		 */
		
		$file = 'app/views/' . preg_replace('{/$}', '', $view) . '.php';

		if ( ! file_exists($file)) {
			return trigger_error('The view file you specified cannot be found from the app/views/ directory', E_USER_ERROR);
		}

		/**
		 * Extract the specific parameters to the view
		 */

		if ($data) {
			extract($data);
		}

		include $file;
	}

}